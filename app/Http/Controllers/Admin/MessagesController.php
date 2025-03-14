<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MessagesController extends AdminController
{
    public function __construct()
    {
        $this->module_name = 'messages';
        $this->module_title = 'Сообщения';
        $this->module_icon = 'fa-regular fa-comments';
        parent::__construct();
    }

    // public function index()
    // {
    //     return view('admin.messages.index');
    // }

    public function index_data(Request $request)
    {
        $query = Message::getChats();

        // Фильтры
        if ($request->id) {
            $query->where('id', $request->id);
        }

        if ($request->from_user_id) {
            $query->where(function($q) use ($request) {
                $q->where('from_user_id', $request->from_user_id)
                  ->orWhere('to_user_id', $request->from_user_id);
            });
        }

        if ($request->to_user_id) {
            $query->where(function($q) use ($request) {
                $q->where('from_user_id', $request->to_user_id)
                  ->orWhere('to_user_id', $request->to_user_id);
            });
        }

        if ($request->has_file !== null) {
            $query->where('file', $request->has_file);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return DataTables::of($query)
            ->editColumn('from_user', function ($message) {
                return $message->fromUser->name;
            })
            ->editColumn('to_user', function ($message) {
                return $message->toUser->name;
            })
            ->editColumn('message', function ($message) {
                return \Str::limit($message->message, 50);
            })
            ->editColumn('file', function ($message) {
                if ($message->file) {
                    return '<i class="fas fa-file" title="' . $message->file_name . '"></i>';
                }
                return '';
            })
            ->editColumn('status', function ($message) {
                $unreadCount = Message::where(function($q) use ($message) {
                    $q->where('from_user_id', $message->from_user_id)
                      ->where('to_user_id', $message->to_user_id);
                })->orWhere(function($q) use ($message) {
                    $q->where('from_user_id', $message->to_user_id)
                      ->where('to_user_id', $message->from_user_id);
                })->where('is_read', false)->count();

                return $unreadCount > 0 ?
                    '<span class="badge bg-warning">Непрочитанных: ' . $unreadCount . '</span>' :
                    '<span class="badge bg-success">Все прочитаны</span>';
            })
            ->editColumn('created_at', function ($message) {
                return Carbon::parse($message->created_at)->format('d.m.Y H:i');
            })
            ->rawColumns(['file', 'status'])
            ->make(true);
    }

    public function show(Request $request, $fromUserId)
    {
        $messages = Message::where(function($query) use ($request, $fromUserId) {
                $query->where('from_user_id', $fromUserId)
                      ->where('to_user_id', $request->to_user_id);
            })
            ->orWhere(function($query) use ($request, $fromUserId) {
                $query->where('from_user_id', $request->to_user_id)
                      ->where('to_user_id', $fromUserId);
            })
            ->with(['fromUser', 'toUser'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    public function destroy(Message $message)
    {
        if ($message->file) {
            // Удаляем файл, если он есть
            $filePath = public_path('storage/' . $message->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Сообщение успешно удалено'
        ]);
    }
}
