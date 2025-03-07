<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\View;

class FeedbacksController extends AdminController
{
    public function __construct()
    {
        $this->module_title = 'Отзывы';
        $this->module_name = 'feedbacks';
        $this->module_path = 'admin';
        $this->module_icon = 'fa-solid fa-star';
        $this->module_model = "App\Models\Feedback";
        parent::__construct();
    }

    public function index_data(Request $request)
    {
        $feedbacks = Feedback::with(['user', 'manufacturer'])->select('feedbacks.*');

        return Datatables::of($feedbacks)
            ->filter(function ($query) use ($request) {
                if ($request->id) {
                    $query->where('feedbacks.id', 'like', "%{$request->id}%");
                }
                if ($request->user_id) {
                    $query->where('feedbacks.user_id', $request->user_id);
                }
                if ($request->manufacture_user_id) {
                    $query->where('feedbacks.manufacture_user_id', $request->manufacture_user_id);
                }
                if ($request->rating) {
                    $query->where('feedbacks.rating', $request->rating);
                }
                if ($request->date_from) {
                    $query->whereDate('feedbacks.created_at', '>=', $request->date_from);
                }
                if ($request->date_to) {
                    $query->whereDate('feedbacks.created_at', '<=', $request->date_to);
                }
            })
            ->addColumn('user_name', function ($feedback) {
                return $feedback->user ? $feedback->user->name : 'Удален';
            })
            ->addColumn('manufacturer_name', function ($feedback) {
                return $feedback->manufacturer ? $feedback->manufacturer->name : 'Удален';
            })
            ->addColumn('rating_stars', function ($feedback) {
                $stars = '';
                for ($i = 1; $i <= 5; $i++) {
                    $stars .= $i <= $feedback->rating ? '★' : '☆';
                }
                return '<span class="text-warning">' . $stars . '</span>';
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d.m.Y H:i');
            })
            ->rawColumns(['rating_stars'])
            ->make(true);
    }

    public function show(Feedback $feedback)
    {
        View::share('module_action', 'Просмотр');
        return view('admin.feedbacks.show', compact('feedback'));
    }

    public function destroy($id)
    {
        $feedback = $this->module_model::findOrFail($id);
        $feedback->delete();

        flash('<i class="fas fa-check"></i> Отзыв успешно удален')->success()->important();

        return redirect()->back();
    }
}
