<?php

namespace Modules\Post\Http\Controllers\Admin;

use App\Authorizable;
use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\Post\Enums\PostStatus;
use Modules\Post\Enums\PostType;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class PostsController extends AdminBaseController
{
    use Authorizable;

    public function __construct()
    {
        $this->module_title = 'Новости';
        $this->module_name = 'posts';
        $this->module_path = 'post::admin';
        $this->module_icon = 'fa-regular fa-file-lines';
        $this->module_model = "Modules\Post\Models\Post";
        parent::__construct();
    }

    /**
     * Store a new resource in the database.
     *
     * @param  Request  $request  The request object containing the data to be stored.
     * @return RedirectResponse The response object that redirects to the index page of the module.
     *
     * @throws Exception If there is an error during the creation of the resource.
     */
    public function store(Request $request)
    {
        $module_name_singular = Str::singular($this->module_name);

        $validated_data = $request->validate([
            'title' => 'required|max:191',
            'slug' => 'nullable|max:191',
            'created_by_alias' => 'nullable|max:191',
            'intro' => 'required',
            'content' => 'required',
            'image' => 'required|max:191',
            // 'category_id' => 'required|integer',
            'type' => Rule::enum(PostType::class),
            'is_featured' => 'required|integer',
            'tags_list' => 'nullable|array',
            'status' => Rule::enum(PostStatus::class),
            'published_at' => 'required|date',
            'meta_title' => 'nullable|max:191',
            'meta_keywords' => 'nullable|max:191',
            'order' => 'nullable|integer',
            'meta_description' => 'nullable',
            'meta_og_image' => 'nullable|max:191',
        ]);

        $data = Arr::except($validated_data, 'tags_list');
        $data['created_by_name'] = auth()->user()->name;

        $$module_name_singular = $this->module_model::create($data);
        $$module_name_singular->tags()->attach($request->input('tags_list'));

        flash("Новый '".Str::singular($this->module_title)."' добавлен")->success()->important();

        logUserAccess($this->module_title.' Store | Id: '.$$module_name_singular->id);

        return redirect("admin/{$this->module_name}");
    }

    /**
     * Updates a resource.
     *
     * @param  int  $id
     * @param  Request  $request  The request object.
     * @param  mixed  $id  The ID of the resource to update.
     * @return Response
     * @return RedirectResponse The redirect response.
     *
     * @throws ModelNotFoundException If the resource is not found.
     */
    public function update(Request $request, $id)
    {
        $module_name_singular = Str::singular($this->module_name);

        $validated_data = $request->validate([
            'title' => 'required|max:191',
            'slug' => 'nullable|max:191',
            'created_by_alias' => 'nullable|max:191',
            'intro' => 'required',
            'content' => 'required',
            'image' => 'required|max:191',
            // 'category_id' => 'required|integer',
            'type' => Rule::enum(PostType::class),
            'is_featured' => 'required|integer',
            'tags_list' => 'nullable|array',
            'status' => Rule::enum(PostStatus::class),
            'published_at' => 'required|date',
            'meta_title' => 'nullable|max:191',
            'meta_keywords' => 'nullable|max:191',
            'order' => 'nullable|integer',
            'meta_description' => 'nullable',
            'meta_og_image' => 'nullable|max:191',
        ]);

        $data = Arr::except($validated_data, 'tags_list');

        $$module_name_singular = $this->module_model::findOrFail($id);
        $$module_name_singular->update($data);

        $tags_list = $request->input('tags_list', []);
        $$module_name_singular->tags()->sync($tags_list);

        flash(Str::singular($this->module_title)." успешно обновлен")->success()->important();

        logUserAccess($this->module_title.' Update | Id: '.$$module_name_singular->id);

        return redirect()->route("admin.{$this->module_name}.show", $$module_name_singular->id);
    }

    public function index_data()
    {
        $module_name = $this->module_name;
        $module_model = $this->module_model;

        $$module_name = $module_model::query();

        return Datatables::of($$module_name)
            ->addColumn('action', function ($data) {
                $module_name = $this->module_name;

                return view('admin.includes.action_column', compact('module_name', 'data'));
            })
            ->editColumn('title', '<strong>{{$title}}</strong>')
            ->editColumn('updated_at', function ($data) {
                return $data->updated_at->format('d.m.Y H:i');
            })
            ->rawColumns(['title', 'action'])
            ->orderColumns(['id'], '-:column $1')
            ->make(true);
    }
}
