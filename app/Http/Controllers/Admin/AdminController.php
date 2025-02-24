<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;

class AdminController extends Controller
{
    public $module_title;
    public $module_name;
    public $module_path;
    public $module_icon;
    public $module_model;

    public function __construct()
    {
        $this->setGlobalVars();
    }

    protected function setGlobalVars()
	{
		View::share('module_title', $this->module_title);
		View::share('module_name', $this->module_name);
		View::share('module_path', $this->module_path);
		View::share('module_icon', $this->module_icon);
		View::share('module_model', $this->module_model);
		View::share('module_name_singular', Str::singular($this->module_name ?? ''));
	}

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * List of trashed ertries
     * works if the softdelete is enabled.
     */
    public function trashed()
    {
		View::share('module_action', 'Корзина');
        ${$this->module_name} = $this->module_model::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate();
        return view("{$this->module_path}.{$this->module_name}.trash", compact("{$this->module_name}"));
    }

     /**
     * Restores a data entry in the database.
     */
    public function restore($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Восстановление';

        $$module_name_singular = $module_model::withTrashed()->find($id);
        $$module_name_singular->restore();

        flash(label_case($module_name_singular).' Запись успешно восстановлена')->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect("admin/{$module_name}");
    }
}
