<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Callback;
use App\Enums\CallbackStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Обратные звонки
 */
class CallbacksController extends AdminController
{
    public function __construct()
    {
        $this->module_name = 'callbacks';
        $this->module_path = 'callbacks';
        $this->module_title = 'Обратные звонки';
        $this->module_icon = 'fas fa-phone';
        $this->module_model = "App\Models\Callback";
        parent::__construct();
    }

    public function index_data(Request $request)
    {
        $query = Callback::query()
            ->with(['updater'])
            ->orderBy('created_at', 'desc');

        if ($request->get('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->get('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->get('phone')) {
            $query->where('phone', 'like', '%' . $request->get('phone') . '%');
        }

        if ($request->get('email')) {
            $query->where('email', 'like', '%' . $request->get('email') . '%');
        }

        if ($request->get('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->get('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        return Datatables::of($query)
            ->editColumn('status', function ($row) {
                return sprintf(
                    '<span class="badge bg-%s">%s</span>',
                    $row->status->color(),
                    $row->status->label()
                );
            })
            ->addColumn('updater', function ($row) {
                if ($row->updated_by) {
                    return sprintf(
                        '%s<br><small class="text-muted">%s</small>',
                        $row->updater->name,
                        $row->updated_at->format('d.m.Y H:i')
                    );
                }
                return '-';
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d.m.Y H:i');
            })
            ->editColumn('comment', function ($row) {
                return Str::limit($row->comment, 50);
            })
            ->rawColumns(['status', 'updater'])
            ->make(true);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'comment' => 'nullable|string',
            'status' => 'required|string|in:' . implode(',', CallbackStatus::values()),
        ]);

        $callback = Callback::findOrFail($id);

        // Добавляем updated_by автоматически
        $request->merge(['updated_by' => auth()->id()]);

        $callback->update($request->all());

        flash("<i class='fas fa-check'></i> Запись успешно обновлена")->success()->important();

        return redirect()->route("admin.{$this->module_name}.index");
    }

}
