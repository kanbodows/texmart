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
use Illuminate\Support\Facades\Storage;
use App\Models\Response;
use Illuminate\Http\JsonResponse;

class AnnouncesController extends AdminController
{
    use Authorizable;

    public function __construct()
    {
        $this->module_title = 'Объявления';
        $this->module_name = 'announces';
        $this->module_path = 'announces';
        $this->module_model = 'App\Models\Announce';
        $this->module_icon = 'fas fa-bullhorn';
        parent::__construct();
    }

    public function index()
    {
        $announces = Announce::with('user', 'category')->paginate(20);
		View::share('module_action', 'Список');
		View::share('categories', \App\Models\Category::all());
        return view('admin.announces.index', compact('announces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
		View::share('categories', \App\Models\Category::all());
		View::share('module_action', 'Создать');

        return view('admin.announces.create_edit');
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
                $path = $image->store('announces', 'public');
                $images[] = $path;
            }
        }

        $announce = Announce::create(array_merge(
            $request->validated(),
            ['images' => $images]
        ));

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
     */
    public function update(Request $request, $id)
    {
        $announce = Announce::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:191',
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

        $images = $announce->images ?? [];

        // Добавление новых изображений
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('announces', 'public');
                $images[] = $path;
            }
        }

        // Удаление отмеченных изображений
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $index) {
                if (isset($images[$index])) {
                    Storage::disk('public')->delete($images[$index]);
                    unset($images[$index]);
                }
            }
            $images = array_values($images); // переиндексируем массив
        }

        $announce->update(array_merge(
            $data,
            ['images' => $images]
        ));

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

        return response()->json(['success' => true]);
        // return redirect()
        //     ->route('admin.announces.index')
        //     ->with('success', 'Объявление успешно удалено');
    }

    public function index_data(Request $request)
    {
        $announces = Announce::query()->with(['category', 'user'])->withCount('responses');

        return Datatables::of($announces)
            ->filter(function ($query) use ($request) {
                // Фильтр по ID
                if ($request->has('id') && !empty($request->id)) {
                    $query->where('id', 'like', "%{$request->id}%");
                }
                // Фильтр по названию
                if ($request->has('name') && !empty($request->name)) {
                    $query->where('name', 'like', "%{$request->name}%");
                }
                // Фильтр по категории
                if ($request->has('category_id') && !empty($request->category_id)) {
                    $query->where('category_id', $request->category_id);
                }
                // Фильтр по статусу
                if ($request->has('status') && !empty($request->status)) {
                    $query->where('status', $request->status);
                }
                // Фильтр по цене
                if ($request->has('price_min') && !empty($request->price_min)) {
                    $query->where('price', '>=', $request->price_min);
                }
                if ($request->has('price_max') && !empty($request->price_max)) {
                    $query->where('price', '<=', $request->price_max);
                }
                // Фильтр по местоположению
                if ($request->has('locate') && !empty($request->locate)) {
                    $query->where('locate', 'like', "%{$request->locate}%");
                }
                // Фильтр по дате обновления
                if ($request->has('date_from') && !empty($request->date_from)) {
                    $query->whereDate('updated_at', '>=', $request->date_from);
                }
                if ($request->has('date_to') && !empty($request->date_to)) {
                    $query->whereDate('updated_at', '<=', $request->date_to);
                }
            })
            ->editColumn('content', function ($data) {
                return '<a href="' . route('admin.announces.edit', $data->id) . '">' . $data->content . '</a>';
            })
            ->editColumn('price', function ($data) {
                if ($data->price) {
                    return number_format($data->price, 0, '.', ' ') . ' ' . $data->currency;
                }
                return 'Договорная';
            })
            ->addColumn('user_name', function ($data) {
                return $data->user ? $data->user->name : $data->title;
            })
            ->addColumn('status_label', function ($data) {
                return '<span class="badge bg-' . $data->status->color() . '">' .
                       $data->status->label() . '</span>';
            })
            ->editColumn('category_id', function ($data) {
                return $data->category ? $data->category->title : '-';
            })
            ->editColumn('updated_at', function ($data) {
                return $data->updated_at->format('d.m.Y H:i');
            })
            ->rawColumns(['responses_count', 'content', 'status_label'])
            ->make(true);
    }

    // Добавим метод для получения откликов
    public function getResponses($id)
    {
        $responses = Response::with(['user'])
            ->where('announce_id', $id)
            ->select('responses.*');

        return Datatables::of($responses)
            ->addColumn('user_name', function ($response) {
                return $response->user->name;
            })
            ->addColumn('created_at', function ($response) {
                return $response->created_at->format('d.m.Y H:i');
            })
            ->make(true);
    }

    /**
     * Update announce status
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStatus($id, Request $request)
    {
        $announce = $this->module_model::findOrFail($id);

        $request->validate([
            'status' => 'required|in:' . implode(',', \App\Enums\AnnounceStatus::values())
        ]);

        $announce->status = $request->status;
        $announce->save();

        return response()->json(['success' => true]);
    }
}
