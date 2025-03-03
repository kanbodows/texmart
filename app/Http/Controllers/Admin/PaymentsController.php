<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\View;

class PaymentsController extends AdminController
{
    public function __construct()
    {
        $this->module_title = 'Платежи';
        $this->module_name = 'payments';
        $this->module_path = 'admin';
        $this->module_icon = 'fas fa-money-bill';
        $this->module_model = 'App\Models\Payment';
        parent::__construct();
    }

    public function index()
    {
        View::share('module_action', 'Список');
        return view('admin.payments.index');
    }

    public function index_data(Request $request)
    {
        $payments = Payment::with('user')->select('payments.*');

        return DataTables::of($payments)
            ->filter(function ($query) use ($request) {
                if ($request->has('id') && !empty($request->id)) {
                    $query->where('id', 'like', "%{$request->id}%");
                }
                if ($request->has('user_id') && !empty($request->user_id)) {
                    $query->where('user_id', $request->user_id);
                }
                if ($request->has('status') && !empty($request->status)) {
                    $query->where('status', $request->status);
                }
                if ($request->has('payment_method') && !empty($request->payment_method)) {
                    $query->where('payment_method', $request->payment_method);
                }
                if ($request->has('amount_from') && !empty($request->amount_from)) {
                    $query->where('amount', '>=', $request->amount_from);
                }
                if ($request->has('amount_to') && !empty($request->amount_to)) {
                    $query->where('amount', '<=', $request->amount_to);
                }
                if ($request->has('date_from') && !empty($request->date_from)) {
                    $query->whereDate('created_at', '>=', $request->date_from);
                }
                if ($request->has('date_to') && !empty($request->date_to)) {
                    $query->whereDate('created_at', '<=', $request->date_to);
                }
            })
            ->addColumn('user_name', function ($payment) {
                return $payment->user ? '<a href="'.route('admin.users.edit', $payment->user->id).'">'.$payment->user->name.'</a>' : '-';
            })
            ->addColumn('status_badge', function ($payment) {
                return $payment->status_badge;
            })
            ->addColumn('formatted_amount', function ($payment) {
                return $payment->formatted_amount;
            })
            ->editColumn('created_at', function ($payment) {
                return $payment->created_at->format('d.m.Y H:i');
            })
            ->addColumn('action', function ($data) {
                return view('admin.payments.actions', compact('data'));
            })
            ->rawColumns(['user_name', 'status_badge', 'action'])
            ->make(true);
    }

    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        View::share('module_action', 'Просмотр платежа');

        return view('admin.payments.show', compact('payment'));
    }
}
