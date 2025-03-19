<?php

namespace App\Http\Controllers\Admin;

use App\Authorizable;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class SellersConfController extends Controller
{
    use Authorizable;

    protected $filterTypes = [
        'category' => 'Категория',
        'gender' => 'Пол',
        'scale' => 'Масштаб производства',
        'layer' => 'Направление пошива'
    ];

    public function __construct()
    {
        View::share('module_title', "Настройки производителя");
        View::share('module_name', "sellers_conf");
        View::share('module_icon', "fa-solid fa-cogs");
        View::share('module_model', "App\Models\Filter");
    }

    /**
     * Страница настройки справочников
     */
    public function index()
    {
        View::share('module_action', 'Настройки производителя');
        return view("admin.sellers_conf");
    }

    /**
     * Сохранение новой записи
     */
    public function store(Request $request, string $type)
    {
        if (!array_key_exists($type, $this->filterTypes)) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $filter = Filter::create([
            'name' => $validated['name'],
            'filter_key' => $type
        ]);
        logUserAccess("sellers_conf store/{$type} | Id: {$filter->id}");

        return redirect()->back()->with('success', "{$this->filterTypes[$type]} успешно добавлен(а)");
    }

    /**
     * Обновление записи
     */
    public function update(Request $request, string $type, $id)
    {
        if (!array_key_exists($type, $this->filterTypes)) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $filter = Filter::where('filter_key', $type)->findOrFail($id);
        $filter->update([
            'name' => $validated['name']
        ]);
        logUserAccess("sellers_conf update/{$type} | Id: {$filter->id}");

        return redirect()->back()->with('success', "{$this->filterTypes[$type]} успешно обновлен(а)");
    }

    /**
     * Удаление записи
     */
    public function delete(string $type, $id)
    {
        if (!array_key_exists($type, $this->filterTypes)) {
            abort(404);
        }

        $filter = Filter::where('filter_key', $type)->findOrFail($id);
        $filter->delete();
        logUserAccess("sellers_conf delete/{$type} | Id: {$filter->id}");

        return redirect()->back()->with('success', "{$this->filterTypes[$type]} успешно удален(а)");
    }
}
