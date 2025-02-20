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
use Illuminate\Support\Facades\Validator;

class AnnouncesController extends Controller
{
    use Authorizable;

    public $module_title;
    public $module_name;
    public $module_icon;

    public function __construct()
    {
        $this->module_title = 'Объявления';
        $this->module_name = 'announces';
        $this->module_icon = 'fas fa-bullhorn';
    }

    public function index()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;

        $announces = Announce::with('user', 'category')->paginate(20);

        return view('admin.announces.index', compact('module_title', 'module_name', 'module_icon', 'announces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;

        return view('admin.announces.create', compact('module_title', 'module_name', 'module_icon'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\View\View
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'content' => 'required|string',
            'phone' => 'required|string|max:191',
            'code' => 'required|string|max:191|unique:announces',
            'email' => 'nullable|email|max:191',
            'user_id' => 'required|integer|exists:users,id',
            'locate' => 'nullable|string|max:191',
            'category_id' => 'nullable|integer|exists:categories,id',
            'price' => 'nullable|integer',
            'currency' => 'nullable|string|max:191',
            'date' => 'nullable|date',
            'check' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Announce::create($validator->validated());

        return redirect()
            ->route('admin.announces.index')
            ->with('success', 'Объявление успешно создано');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Show';

        $$module_name_singular = $module_model::findOrFail($id);

        $users = User::role($$module_name_singular->name)->get();

        Log::info(label_case($module_title.' '.$module_action).' | User:'.auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return view(
            "admin.{$module_name}.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "{$module_name_singular}", 'users')
        );
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
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;

        $announce = Announce::findOrFail($id);

        return view('admin.announces.edit', compact('module_title', 'module_name', 'module_icon', 'announce'));
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

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'content' => 'required|string',
            'phone' => 'required|string|max:191',
            'code' => 'required|string|max:191|unique:announces,code,' . $id,
            'email' => 'nullable|email|max:191',
            'user_id' => 'required|integer|exists:users,id',
            'locate' => 'nullable|string|max:191',
            'category_id' => 'nullable|integer|exists:categories,id',
            'price' => 'nullable|integer',
            'currency' => 'nullable|string|max:191',
            'date' => 'nullable|date',
            'check' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $announce->update($validator->validated());

        return redirect()
            ->route('admin.announces.index')
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

        return redirect()
            ->route('admin.announces.index')
            ->with('success', 'Объявление успешно удалено');
    }
}
