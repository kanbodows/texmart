<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Response;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\View;

class ResponsesController extends AdminController
{
    public function __construct()
    {
        $this->module_title = 'Отклики';
        $this->module_name = 'responses';
        $this->module_path = 'admin';
        $this->module_icon = 'fa-solid fa-comments';
        $this->module_model = "App\Models\Response";
        parent::__construct();
    }

    public function index_data(Request $request)
    {
        $responses = Response::with(['user', 'announce'])->select('responses.*');

        return Datatables::of($responses)
            ->filter(function ($query) use ($request) {
                if ($request->id) {
                    $query->where('responses.id', 'like', "%{$request->id}%");
                }
                if ($request->user_id) {
                    $query->where('responses.user_id', $request->user_id);
                }
                if ($request->announce_id) {
                    $query->where('responses.announce_id', $request->announce_id);
                }
                if ($request->date_from) {
                    $query->whereDate('responses.created_at', '>=', $request->date_from);
                }
                if ($request->date_to) {
                    $query->whereDate('responses.created_at', '<=', $request->date_to);
                }
            })
            ->addColumn('user_name', function ($response) {
                return $response->user ? $response->user->name : 'Удален';
            })
            ->addColumn('announce_title', function ($response) {
                return $response->announce ? $response->announce->title : 'Удалено';
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d.m.Y H:i');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show(Response $response)
    {
        View::share('module_action', 'Просмотр');
        return view('admin.responses.show', compact('response'));
    }

    /**
     * Удаляет отклик
     */
    public function destroy($id)
    {
        $response = $this->module_model::findOrFail($id);
        $response->delete();

        flash('<i class="fas fa-check"></i> Отклик успешно удален')->success()->important();

        return response()->json(['success' => true]);
        // return redirect()->back();
    }
}
