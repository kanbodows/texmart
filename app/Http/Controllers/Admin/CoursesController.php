<?php

namespace App\Http\Controllers\Admin;

use Modules\Post\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Modules\Post\Enums\PostType;
class CoursesController extends AdminController
{
    public function __construct()
    {
        $this->module_name = 'post';
        $this->module_path = 'courses';
        $this->module_title = 'База знаний';
        $this->module_icon = 'fas fa-graduation-cap';
        $this->module_model = "Modules\Post\Models\Post";
        parent::__construct();
    }

    public function index_data(Request $request)
    {
        $query = Post::query()->where('type', 'courses')
            ->with('creator')
            ->orderBy('order', 'asc');

        if ($request->get('created_by')) {
            $query->where('created_by', $request->get('created_by'));
        }

        if ($request->get('status') !== null) {
            $query->where('status', $request->get('status'));
        }

        if ($request->get('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->get('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        return Datatables::of($query)
            ->editColumn('title', function ($row) {
                return '<a href="'.route('admin.courses.edit', $row->id).'">'.$row->title.'</a>';
            })
            ->addColumn('created_by_name', function ($row) {
                return $row->creator ? $row->creator->name : '';
            })
            ->editColumn('status', function ($row) {
                return $row->status ? '<span class="badge bg-success">Активно</span>' : '<span class="badge bg-danger">Неактивно</span>';
            })
            ->editColumn('order', function ($row) {
                return $row->order ?: '-';
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d.m.Y H:i');
            })
            ->rawColumns(['title', 'status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
            'order' => 'nullable|integer',
            'video' => 'nullable|string'
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->type = PostType::COURSES->value;
        $post->status = $request->has('status');
        $post->created_by = auth()->id();
        $post->order = $request->order;
        $post->video = $request->video;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public');
            $post->image = $path;
        }

        $post->save();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Запись успешно создана');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
            'order' => 'nullable|integer',
            'video' => 'nullable|string'
        ]);

        $post = Post::findOrFail($id);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->status = $request->has('status');
        $post->order = $request->order;
        $post->video = $request->video;

        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('courses', 'public');
            $post->image = $path;
        }

        $post->save();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Запись успешно обновлена');
    }

    public function update_status(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->status = $request->status;
        $post->save();

        return response()->json(['success' => true]);
    }
}
