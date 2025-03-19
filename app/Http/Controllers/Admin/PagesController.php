<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Post\Enums\PostType;
use Modules\Post\Models\Post;
use Illuminate\Support\Str;

class PagesController extends AdminController
{
    public function __construct()
    {
        $this->module_name = 'pages';
        $this->module_path = 'pages';
        $this->module_title = 'Статические страницы';
        $this->module_icon = 'fas fa-file-alt';
        $this->module_model = "Modules\Post\Models\Post";
        parent::__construct();
    }

    public function index_data()
    {
        $query = Post::query()
            ->where('type', PostType::STATIC_PAGE)
            ->with('updater');

        return Datatables::of($query)
            ->editColumn('status', function ($row) {
                return $row->status ?
                    '<span class="badge bg-success">Активна</span>' :
                    '<span class="badge bg-warning">Отключена</span>';
            })
            ->editColumn('updated_at', function ($row) {
                return [
                    'display' => $row->updated_at->format('d.m.Y H:i'),
                    'timestamp' => $row->updated_at->timestamp
                ];
            })
            ->addColumn('updater_name', function ($row) {
                return $row->updater ? $row->updater->name : '-';
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'order' => 'nullable|integer',
            'status' => 'boolean'
        ]);

        $request->merge([
            'type' => PostType::STATIC_PAGE->value,
            'updated_by' => auth()->id()
        ]);

        if (empty($request->slug)) {
            $request->merge(['slug' => Str::slug($request->title)]);
        }

        $page = Post::create($request->all());

        flash("<i class='fas fa-check'></i> Страница успешно создана")->success()->important();

        return redirect()->route("admin.{$this->module_name}.index");
    }

    public function edit($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_action = 'Редактирование';

        $page = Post::where('type', PostType::STATIC_PAGE)->findOrFail($id);

        return view("admin.{$module_name}.create_edit",
            compact('module_title', 'module_name', 'module_icon', 'module_action', 'page'));
    }

    public function update(Request $request, $id)
    {
        $page = Post::where('type', PostType::STATIC_PAGE)->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $id,
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'order' => 'nullable|integer',
            'status' => 'boolean'
        ]);

        $request->merge(['updated_by' => auth()->id()]);

        if (empty($request->slug)) {
            $request->merge(['slug' => Str::slug($request->title)]);
        }

        $page->update($request->all());

        flash("<i class='fas fa-check'></i> Страница успешно обновлена")->success()->important();

        return redirect()->route("admin.{$this->module_name}.index");
    }
}
