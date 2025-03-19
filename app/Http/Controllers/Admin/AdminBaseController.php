<?php

namespace App\Http\Controllers\Admin;

use App\Authorizable;
use App\Http\Controllers\Admin\AdminController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class AdminBaseController extends AdminController
{
    use Authorizable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $module_name_singular = Str::singular($this->module_name);
        $module_model = $this->module_model;
        ${$this->module_name} = $module_model::paginate(15);

        $module_action = 'Список';
        // logUserAccess($this->module_title.' '.$module_action);

        return view("{$module_name_singular}::admin.{$this->module_name}.index",
            compact( "{$this->module_name}", 'module_action')
        );
    }

    /**
     * Retrieves the data for the index page of the module.
     *
     * @return Illuminate\Http\JsonResponse
     */
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
            ->editColumn('name', '<strong>{{$name}}</strong>')
            ->editColumn('updated_at', function ($data) {
                $diff = Carbon::now()->diffInHours($data->updated_at);
                if ($diff < 25) {
                    return $data->updated_at->diffForHumans();
                }
                return $data->updated_at->isoFormat('llll');
            })
            ->rawColumns(['name', 'action'])
            ->orderColumns(['id'], '-:column $1')
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $module_action = 'Создать';
        $module_name_singular = Str::singular($this->module_name);
        // logUserAccess($module_title.' '.$module_action);

        view()->share('module_name_singular', $module_name_singular);
        return view(
            "{$module_name_singular}::admin.{$this->module_name}.create_edit",
            compact('module_action')
        );
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
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Сохранение';

        $$module_name_singular = $module_model::create($request->all());

        flash("Новый '".Str::singular($module_title)."' добавлен")->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect("admin/{$module_name}");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $module_name = $this->module_name;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        $module_action = 'Просмотр';

        $$module_name_singular = $module_model::findOrFail($id);
        // logUserAccess($this->module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);
        return view(
            "{$this->module_path}.{$this->module_name}.show",
            compact('module_action', "{$module_name_singular}")
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Редактировать';

        $$module_name_singular = $module_model::findOrFail($id);

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return view(
            "{$module_path}.{$module_name}.create_edit",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "{$module_name_singular}")
        );
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
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Обновить';

        $$module_name_singular = $module_model::findOrFail($id);

        $$module_name_singular->update($request->all());

        flash(Str::singular($module_title)."' успешно обновлен")->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect()->route("admin.{$module_name}.show", $$module_name_singular->id);
    }

    /**
     * Destroys a record from the database.
     *
     * @param  int  $id
     * @param  int  $id  The ID of the record to be destroyed.
     * @return Response
     * @return \Illuminate\Http\RedirectResponse Redirects the user to the specified URL.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the record is not found.
     */
    public function destroy($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Удалить';

        $$module_name_singular = $module_model::findOrFail($id);

        $$module_name_singular->delete();

        flash(label_case($module_name_singular).' успешно удален!')->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect("admin/{$module_name}");
    }

    /**
     * List of trashed ertries
     * works if the softdelete is enabled.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function trashed()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;

        $module_action = 'Список удаленных';

        $$module_name = $module_model::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate();

        logUserAccess($module_title.' '.$module_action);

        return view(
            "{$module_path}.{$module_name}.trash",
            compact('module_title', 'module_name', 'module_path', "{$module_name}", 'module_icon', 'module_action')
        );
    }

    /**
     * Restores a data entry in the database.
     *
     * @param  Request  $request
     * @param  int  $id
     * @param  int  $id  The ID of the data entry to be restored.
     * @return Response
     * @return \Illuminate\Http\RedirectResponse The response redirecting to the admin page of the module.
     *
     * @throws \Exception If the data entry cannot be found or restored.
     */
    public function restore($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Восстановить';

        $$module_name_singular = $module_model::withTrashed()->find($id);
        $$module_name_singular->restore();

        flash(label_case($module_name_singular).' Данные успешно восстановлены!')->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect("admin/{$module_name}");
    }
}
