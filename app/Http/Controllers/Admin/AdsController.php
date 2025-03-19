<?php

namespace App\Http\Controllers\Admin;

use Modules\Post\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Modules\Post\Enums\PostType;

class AdsController extends AdminController
{
    public function __construct()
    {
        $this->module_name = 'post';
        $this->module_path = 'ads';
        $this->module_title = 'Рекламные баннеры';
        $this->module_icon = 'fas fa-ad';
        $this->module_model = "Modules\Post\Models\Post";
        parent::__construct();
    }

    public function index_data(Request $request)
    {
        $query = Post::query()->where('type', 'ads')
            ->with('creator')
            ->orderBy('order', 'asc');

        if ($request->get('status') !== null) {
            $query->where('status', $request->get('status'));
        }

        return Datatables::of($query)
            ->editColumn('title', function ($row) {
                return '<a href="'.route('admin.ads.edit', $row->id).'">'.$row->title.'</a>';
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
        $post->type = PostType::ADS->value;
        $post->status = $request->has('status');
        $post->created_by = auth()->id();
        $post->order = $request->order;
        $post->video = $request->video;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ads', 'public');
            $post->image = $path;
        }

        $post->save();

        return redirect()
            ->route('admin.ads.index')
            ->with('success', 'Баннер успешно создан');
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
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('ads', 'public');
            $post->image = $path;
        }

        $post->save();

        return redirect()
            ->route('admin.ads.index')
            ->with('success', 'Баннер успешно обновлен');
    }
}
