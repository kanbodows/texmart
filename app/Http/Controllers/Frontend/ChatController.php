<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Events\NewChatMessage;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Получаем последние сообщения для каждого диалога
        $latestMessages = DB::table('messages as m1')
            ->select('m1.*')
            ->join(DB::raw('(
                SELECT
                    LEAST(from_user_id, to_user_id) as user1,
                    GREATEST(from_user_id, to_user_id) as user2,
                    MAX(created_at) as max_created_at
                FROM messages
                GROUP BY
                    LEAST(from_user_id, to_user_id),
                    GREATEST(from_user_id, to_user_id)
            ) as m2'), function($join) {
                $join->on(function($query) {
                    $query->whereRaw('LEAST(m1.from_user_id, m1.to_user_id) = m2.user1')
                          ->whereRaw('GREATEST(m1.from_user_id, m1.to_user_id) = m2.user2')
                          ->whereRaw('m1.created_at = m2.max_created_at');
                });
            })
            ->where(function($query) use ($user) {
                $query->where('m1.from_user_id', $user->id)
                    ->orWhere('m1.to_user_id', $user->id);
            })
            ->orderBy('m1.created_at', 'desc')
            ->get();

        // Преобразуем результаты в нужный формат
        $conversations = collect($latestMessages)->map(function($message) use ($user) {
            $message = (array) $message;
            $otherUserId = $message['from_user_id'] == $user->id
                ? $message['to_user_id']
                : $message['from_user_id'];

            $otherUser = User::find($otherUserId);

            return [
                'user' => $otherUser,
                'last_message' => $message['message'],
                'time' => Carbon::parse($message['created_at']),
                'unread' => Message::where('from_user_id', $otherUserId)
                    ->where('to_user_id', $user->id)
                    ->where('is_read', false)
                    ->count()
            ];
        });

        return view('chat.index', compact('conversations'));
    }

    public function show($userId)
    {
        $currentUser = Auth::user();
        $otherUser = User::findOrFail($userId);

        // Получаем сообщения между пользователями
        $messages = Message::where(function($query) use ($currentUser, $userId) {
                $query->where('from_user_id', $currentUser->id)
                    ->where('to_user_id', $userId);
            })
            ->orWhere(function($query) use ($currentUser, $userId) {
                $query->where('from_user_id', $userId)
                    ->where('to_user_id', $currentUser->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Отмечаем сообщения как прочитанные
        Message::where('from_user_id', $userId)
            ->where('to_user_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chat.show', compact('messages', 'otherUser'));
    }

    public function store(Request $request, $userId)
    {
        $request->validate([
            'message' => 'required_without:file|string|max:1000',
            'file' => 'nullable|file|max:10240|mimes:jpeg,png,gif,mp4,pdf,doc,docx,xls,xlsx'
        ]);

        $data = [
            'from_user_id' => Auth::id(),
            'to_user_id' => $userId,
            'message' => $request->message
        ];

        // Обработка файла, если он был загружен
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('chat_files', 'public');

            $data['file'] = true;
            $data['file_name'] = $fileName;
            $data['file_path'] = $filePath;
            $data['file_type'] = $file->getMimeType();
        }

        $message = Message::create($data);

        // Отправляем событие
        broadcast(new NewChatMessage($message))->toOthers();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => view('chat.partials.message', ['message' => $message])->render()
            ]);
        }

        return back();
    }

    public function markAsRead($userId)
    {
        Message::where('from_user_id', $userId)
            ->where('to_user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = Message::where('to_user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
