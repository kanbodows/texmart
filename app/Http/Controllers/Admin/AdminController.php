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
		View::share('module_name_singular', Str::singular($this->module_name));
	}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.index');
    }
}
