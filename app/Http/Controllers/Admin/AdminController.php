<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\Models\Announce;
use App\Models\User;
use App\Models\Response;
use App\Models\Payment;
use App\Enums\AnnounceStatus;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

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
        View::share('module_action', 'Список');
        return view('admin.'.$this->module_name.'.index');
    }

    public function edit($id)
    {
        $module_name_singular = Str::singular($this->module_name ?? '');
        $$module_name_singular = $this->module_model::findOrFail($id);
        View::share('module_action', 'Изменение');
        return view('admin.'.$this->module_name.'.create_edit', compact("$module_name_singular"));
    }

    /**
     * Updates a resource.
     */
    public function update(Request $request, $id)
    {
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($this->module_name);

        $module_action = 'Обновить';

        $$module_name_singular = $module_model::findOrFail($id);

        $$module_name_singular->update($request->all());

        flash(Str::singular($this->module_title)."' успешно обновлен")->success()->important();

        logUserAccess($this->module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect()->route("admin.{$this->module_name}.show", $$module_name_singular->id);
    }

     /**
     * Show the application dashboard.
     */
    public function index_dashboard()
    {
        $stats = [
            'announces' => $this->getAnnouncesStats(),
            'users' => $this->getUsersStats(),
            'responses' => $this->getResponsesStats(),
            'payments' => $this->getPaymentsStats(),
        ];

        $charts = [
            'userRegistrations' => $this->getUserRegistrationsChart(),
            'announceStatuses' => $this->getAnnounceStatusesChart(),
            'monthlyActivity' => $this->getMonthlyActivityChart(),
            'popularCategories' => $this->getPopularCategoriesChart(),
        ];

        View::share('module_action', 'Панель управления');
        return view('admin.index', compact('stats', 'charts'));
    }

    private function getAnnouncesStats()
    {
        $now = Carbon::now();
        $lastWeek = Carbon::now()->subWeek();

        $currentTotal = Announce::where('status', AnnounceStatus::ACTIVE)->count();
        $lastWeekTotal = Announce::where('status', AnnounceStatus::ACTIVE)
            ->where('created_at', '<=', $lastWeek)
            ->count();

        return [
            'active' => [
                'count' => $currentTotal,
                'change' => $this->calculatePercentChange($lastWeekTotal, $currentTotal),
                'label' => 'Активные объявления',
                'icon' => 'fa-check',
                'color' => 'success'
            ],
            'moderation' => [
                'count' => Announce::where('status', AnnounceStatus::MODERATION)->count(),
                'label' => 'На модерации',
                'icon' => 'fa-clock',
                'color' => 'warning'
            ],
            'rejected' => [
                'count' => Announce::where('status', AnnounceStatus::REJECTED)->count(),
                'label' => 'Отклоненные',
                'icon' => 'fa-times',
                'color' => 'danger'
            ],
            'draft' => [
                'count' => Announce::where('status', AnnounceStatus::DRAFT)->count(),
                'label' => 'Черновики',
                'icon' => 'fa-pencil',
                'color' => 'secondary'
            ]
        ];
    }

    private function getUsersStats()
    {
        $now = Carbon::now();
        $lastWeek = Carbon::now()->subWeek();

        $currentTotal = User::count();
        $lastWeekTotal = User::where('created_at', '<=', $lastWeek)->count();

        return [
            'total' => [
                'count' => $currentTotal,
                'change' => $this->calculatePercentChange($lastWeekTotal, $currentTotal),
                'label' => 'Всего пользователей',
                'icon' => 'fa-users',
                'color' => 'primary'
            ],
            'manufacturers' => [
                'count' => User::whereHas('roles', function($q) {
                    $q->where('name', 'Manufacturer');
                })->count(),
                'label' => 'Производители',
                'icon' => 'fa-industry',
                'color' => 'info'
            ],
            'new' => [
                'count' => User::where('created_at', '>=', $lastWeek)->count(),
                'label' => 'Новые за 7 дней',
                'icon' => 'fa-user-plus',
                'color' => 'success'
            ],
            'blocked' => [
                'count' => User::where('status', UserStatus::BLOCKED)->count(),
                'label' => 'Заблокированные',
                'icon' => 'fa-user-slash',
                'color' => 'danger'
            ]
        ];
    }

    private function getResponsesStats()
    {
        $now = Carbon::now();
        $today = Carbon::today();

        $currentTotal = Response::count();
        $lastWeekTotal = Response::where('created_at', '<=', $now->copy()->subWeek())->count();

        return [
            'total' => [
                'count' => $currentTotal,
                'change' => $this->calculatePercentChange($lastWeekTotal, $currentTotal),
                'label' => 'Всего откликов',
                'icon' => 'fa-comments',
                'color' => 'primary'
            ],
            'today' => [
                'count' => Response::where('created_at', '>=', $today)->count(),
                'label' => 'Новые за сегодня',
                'icon' => 'fa-comment-dots',
                'color' => 'success'
            ],
            'pending' => [
                'count' => Response::where('status', 'pending')->count(),
                'label' => 'Ожидают ответа',
                'icon' => 'fa-clock',
                'color' => 'warning'
            ],
            'completed' => [
                'count' => Response::where('status', 'completed')->count(),
                'label' => 'Завершенные',
                'icon' => 'fa-check-circle',
                'color' => 'info'
            ]
        ];
    }

    private function getPaymentsStats()
    {
        $now = Carbon::now();
        $startOfMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth();

        $currentMonthSum = Payment::where('created_at', '>=', $startOfMonth)
            ->where('status', 'completed')
            ->sum('amount');

        $lastMonthSum = Payment::where('created_at', '>=', $lastMonth->startOfMonth())
            ->where('created_at', '<', $startOfMonth)
            ->where('status', 'completed')
            ->sum('amount');

        return [
            'month_sum' => [
                'count' => $currentMonthSum,
                'change' => $this->calculatePercentChange($lastMonthSum, $currentMonthSum),
                'label' => 'Сумма за месяц',
                'icon' => 'fa-money-bill-wave',
                'color' => 'success',
                'is_money' => true
            ],
            'transactions' => [
                'count' => Payment::where('created_at', '>=', $startOfMonth)->count(),
                'label' => 'Транзакции за месяц',
                'icon' => 'fa-exchange-alt',
                'color' => 'primary'
            ],
            'average' => [
                'count' => Payment::where('created_at', '>=', $startOfMonth)
                    ->where('status', 'completed')
                    ->avg('amount') ?? 0,
                'label' => 'Средний чек',
                'icon' => 'fa-chart-line',
                'color' => 'info',
                'is_money' => true
            ],
            'pending' => [
                'count' => Payment::where('status', 'pending')->count(),
                'label' => 'Ожидают обработки',
                'icon' => 'fa-clock',
                'color' => 'warning'
            ]
        ];
    }

    private function calculatePercentChange($old, $new)
    {
        if ($old == 0) {
            return $new > 0 ? 100 : 0;
        }

        return round((($new - $old) / $old) * 100, 1);
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

    private function getUserRegistrationsChart()
    {
        $days = collect(range(30, 0))->map(function($day) {
            return Carbon::now()->subDays($day)->format('Y-m-d');
        });

        $registrations = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        return [
            'labels' => $days->map(function($date) {
                return Carbon::parse($date)->format('d.m');
            })->toArray(),
            'data' => $days->map(function($date) use ($registrations) {
                return $registrations[$date] ?? 0;
            })->toArray()
        ];
    }

    private function getAnnounceStatusesChart()
    {
        $statuses = collect(AnnounceStatus::cases())->map(function($status) {
            return [
                'label' => $status->label(),
                'count' => Announce::where('status', $status)->count(),
                'color' => match($status) {
                    AnnounceStatus::ACTIVE => '#198754',     // success
                    AnnounceStatus::MODERATION => '#ffc107', // warning
                    AnnounceStatus::REJECTED => '#dc3545',   // danger
                    AnnounceStatus::DRAFT => '#6c757d',      // secondary
                    default => '#0d6efd'                      // primary
                }
            ];
        });

        return [
            'labels' => $statuses->pluck('label')->toArray(),
            'data' => $statuses->pluck('count')->toArray(),
            'colors' => $statuses->pluck('color')->toArray()
        ];
    }

    private function getMonthlyActivityChart()
    {
        $days = collect(range(30, 0))->map(function($day) {
            return Carbon::now()->subDays($day)->format('Y-m-d');
        });

        $announces = Announce::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('count', 'date')
            ->toArray();

        $responses = Response::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('count', 'date')
            ->toArray();

        return [
            'labels' => $days->map(function($date) {
                return Carbon::parse($date)->format('d.m');
            })->toArray(),
            'announces' => $days->map(function($date) use ($announces) {
                return $announces[$date] ?? 0;
            })->toArray(),
            'responses' => $days->map(function($date) use ($responses) {
                return $responses[$date] ?? 0;
            })->toArray()
        ];
    }

    private function getPopularCategoriesChart()
    {
        $categories = Announce::select('category_id', DB::raw('count(*) as count'))
            ->where('status', AnnounceStatus::ACTIVE)
            ->groupBy('category_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return [
            'labels' => $categories->pluck('category_id')->toArray(),
            'data' => $categories->pluck('count')->toArray(),
            'colors' => collect(range(0, 9))->map(function($i) {
                return "hsl({$i}0, 70%, 50%)";
            })->toArray()
        ];
    }
}
