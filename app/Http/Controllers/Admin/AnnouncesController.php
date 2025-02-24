<?php

namespace App\Http\Controllers\Admin;

use App\Authorizable;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;
use App\Http\Controllers\Controller;
use App\Models\Announce;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AnnouncesController extends AdminController
{
    use Authorizable;

    public function __construct()
    {
        $this->module_title = 'Объявления';
        $this->module_name = 'announces';
        $this->module_path = 'admin';
        $this->module_model = 'App\Models\Announce';
        $this->module_icon = 'fas fa-bullhorn';
        parent::__construct();
    }

    public function index()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;

        $announces = Announce::with('user', 'category')->paginate(20);
		View::share('module_action', 'Список');

        return view('admin.announces.index', compact('module_title', 'module_name', 'module_icon', 'announces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
		View::share('module_action', 'Создать');
        $categories = \App\Models\Category::all();

        return view('admin.announces.create_edit', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\View\View
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'content' => 'required|string',
            'phone' => 'required|string|max:191',
            'code' => 'string|max:191',
            'email' => 'nullable|email|max:191',
            'user_id' => 'required|integer|exists:users,id',
            'locate' => 'nullable|string|max:191',
            'category_id' => 'nullable|integer|exists:categories,id',
            'price' => 'nullable|integer',
            'currency' => 'nullable|string|max:191',
            'date' => 'nullable|date',
            'check' => 'nullable|boolean',
            'images' => 'array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if (count($images) >= 5) break;
                $path = $image->store('announces', 'public');
                $images[] = $path;
            }
        }

        $data['images'] = $images;
        Announce::create($data);

        return redirect()->route('admin.announces.index')
            ->with('success', 'Объявление успешно создано');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $announce = Announce::findOrFail($id);
		View::share('module_action', 'Изменение');
        $categories = \App\Models\Category::all();

        return view('admin.announces.create_edit', compact('categories', 'announce'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\View\View
     */
    public function update(Request $request, $id)
    {
        $announce = Announce::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:191',
            'content' => 'required|string',
            'phone' => 'required|string|max:191',
            'code' => 'string|max:191',
            'email' => 'nullable|email|max:191',
            'user_id' => 'integer|exists:users,id',
            'locate' => 'nullable|string|max:191',
            'category_id' => 'nullable|integer|exists:categories,id',
            'price' => 'nullable|integer',
            'currency' => 'nullable|string|max:191',
            'date' => 'nullable|date',
            'check' => 'nullable|boolean',
            'images' => 'array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'existing_images' => 'array|max:5'
        ]);

        $images = $request->input('existing_images', []);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if (count($images) >= 5) break;
                $path = $image->store('announces', 'public');
                $images[] = $path;
            }
        }

        $data['images'] = $images;
        $announce->update($data);

        return redirect()->route('admin.announces.index')
            ->with('success', 'Объявление успешно обновлено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\View\View
     */
    public function destroy($id)
    {
        $announce = Announce::findOrFail($id);
        $announce->delete();

        flash('Объявление успешно удалено!')->success()->important();

        logUserAccess("announces destroy | Id: {$id}");

        return redirect()
            ->route('admin.announces.index')
            ->with('success', 'Объявление успешно удалено');
    }

    public function index_data()
    {
        $announces = Announce::select([
            'id',
            'name',
            'phone',
            'email',
            'price',
            'currency',
            'category_id',
            'locate',
            'check',
            'updated_at'
        ])
        ->with('category:id,title'); // Подгружаем связанную категорию

        return Datatables::of($announces)
            ->addColumn('action', function ($data) {
                return view('admin.announces.actions', compact('data'));
            })
            ->editColumn('name', function ($data) {
                return '<strong><a href="' . route('admin.announces.edit', $data->id) . '">' . $data->name . '</a></strong>';
            })
            ->editColumn('price', function ($data) {
                if ($data->price) {
                    return number_format($data->price, 0, '.', ' ') . ' ' . $data->currency;
                }
                return 'Договорная';
            })
            ->editColumn('category_id', function ($data) {
                return $data->category ? $data->category->title : '-';
            })
            ->editColumn('check', function ($data) {
                return $data->check
                    ? '<span class="badge bg-success">Активно</span>'
                    : '<span class="badge bg-secondary">Неактивно</span>';
            })
            ->editColumn('updated_at', function ($data) {
                return $data->updated_at->format('d.m.Y H:i');
            })
            ->rawColumns(['name', 'check', 'action'])
            ->make(true);
    }
}
