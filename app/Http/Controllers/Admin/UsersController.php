<?php

namespace App\Http\Controllers\Admin;

use App\Authorizable;
use App\Events\Backend\UserCreated;
use App\Events\Backend\UserUpdated;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProvider;
use App\Notifications\UserAccountCreated;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class UsersController extends AdminController
{
    use Authorizable;

    public function __construct()
    {
        $this->module_title = 'Пользователи';
        $this->module_name = 'users';
        $this->module_path = 'admin';
        $this->module_icon = 'fa-solid fa-user-group';
        $this->module_model = "App\Models\User";
        parent::__construct();
    }

    /**
     * Retrieves the index page for the module.
     */
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all();
        View::share('module_action', 'Список');
        return view('admin.users.index', compact('roles'));
    }

    public function index_data(Request $request)
    {
        $users = User::with('roles')->select('users.*');

        return Datatables::of($users)
            ->filter(function ($query) use ($request) {
                if (!empty($request->id)) {
                    $query->where('users.id', 'like', "%{$request->id}%");
                }
                if (!empty($request->name)) {
                    $query->where('users.name', 'like', "%{$request->name}%");
                }
                if (!empty($request->email)) {
                    $query->where('users.email', 'like', "%{$request->email}%");
                }
                if (!empty($request->status) && $request->status !== '') {
                    $query->where('users.status', $request->status);
                }
                if (!empty($request->roles)) {
                    $query->whereHas('roles', function ($q) use ($request) {
                        $q->where('name', $request->roles);
                    });
                }
            })
            ->addColumn('status', function ($user) {
                return $user->status ? '<span class="badge bg-success">Активный</span>' : '<span class="badge bg-danger">Неактивный</span>';
            })
            ->addColumn('user_roles', function ($user) {
                $roles = [];
                foreach ($user->roles()->pluck('name') as $role) {
                    $roles[] = '<span class="badge bg-primary">' . $role . '</span>';
                }
                return implode(' ', $roles);
            })
            ->addColumn('action', function ($data) {
                return view('admin.includes.user_actions', compact('data'));
            })
            ->rawColumns(['status', 'user_roles', 'action'])
            ->make(true);
    }

    /**
     * Retrieves a list of items based on the search term.
     */
    public function index_list(Request $request)
    {
        $module_action = 'Index List';

        $term = trim($request->q);

        if (empty($term)) {
            return response()->json([]);
        }

        $query_data = $this->module_model::where('name', 'LIKE', "%{$term}%")->orWhere('email', 'LIKE', "%{$term}%")->limit(10)->get();

        $$this->module_name = [];

        foreach ($query_data as $row) {
            $$this->module_name[] = [
                'id' => $row->id,
                'text' => $row->name.' (Email: '.$row->email.')',
            ];
        }

        logUserAccess($this->module_title.' '.$module_action);

        return response()->json($$this->module_name);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::get();
        $permissions = Permission::select('name', 'id')->orderBy('id')->get();
        View::share('module_action', 'Создать');

        return view('admin.users.create_edit', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create($request->validated());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');
        View::share('module_action', 'Просмотр');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::get();
        $user->load('roles');
        $permissions = Permission::select('name', 'id')->orderBy('id')->get();
        View::share('module_action', 'Редактировать');

        return view('admin.users.create_edit', compact('roles', 'user', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->validated());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();
    }

    /**
     * Restores a record in the database.
     *
     * @param  int  $id  The ID of the record to be restored.
     * @return Illuminate\Http\RedirectResponse The redirect response to the admin module page.
     */
    public function restore($id)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = $this->module_model::withTrashed()->find($id);
        $user->restore();

        return back();
    }

    /**
     * Block a user.
     *
     * @param  int  $id  The ID of the user to block.
     * @return Illuminate\Http\RedirectResponse
     *
     * @throws Exception There was a problem updating this user. Please try again.
     */
    public function block($id)
    {
        if (! auth()->user()->can('delete_users')) {
            abort(403);
        }

        $module_action = 'Блокировка';

        if (auth()->user()->id == $id || $id == 1) {
            flash("Вы не можете заблокировать этого пользователя!")->success()->important();

            Log::notice(label_case($this->module_title.' '.$module_action).' Ошибка | Пользователь:'.auth()->user()->name.'(ID:'.auth()->user()->id.')');

            return redirect()->back();
        }

        $$this->module_name_singular = User::withTrashed()->find($id);
        $$this->module_name_singular->status = 2;
        $$this->module_name_singular->save();

        event(new UserUpdated($$this->module_name_singular));

        flash($$this->module_name_singular->name.' Пользователь успешно заблокирован!')->success()->important();

        logUserAccess("{$this->module_title} {$module_action} {$$this->module_name_singular->name} ($id)");

        return redirect()->back();
    }

    /**
     * Unblock a user.
     *
     * @param  int  $id  The ID of the user to unblock.
     * @return RedirectResponse The redirect back to the previous page.
     *
     * @throws Exception If there is a problem updating the user.
     */
    public function unblock($id)
    {
        if (! auth()->user()->can('delete_users')) {
            abort(403);
        }

        $module_action = 'Разблокировка';

        if (auth()->user()->id == $id || $id == 1) {
            flash("Вы не можете разблокировать этого пользователя!")->warning()->important();

            Log::notice(label_case($this->module_title.' '.$module_action).' Ошибка | Пользователь:'.auth()->user()->name.'(ID:'.auth()->user()->id.')');

            return redirect()->back();
        }

        $$this->module_name_singular = User::withTrashed()->find($id);
        $$this->module_name_singular->status = 1;
        $$this->module_name_singular->save();

        event(new UserUpdated($$this->module_name_singular));

        flash($$this->module_name_singular->name.' - Пользователь успешно разблокирован!')->success()->important();

        logUserAccess("{$this->module_title} {$module_action} {$$this->module_name_singular->name} ($id)");

        return redirect()->back();
    }

    /**
     * Destroy a user provider.
     *
     * @param  Request  $request  The request object.
     * @return void
     *
     * @throws Exception There was a problem updating this user. Please try again.
     */
    public function userProviderDestroy(Request $request)
    {
        $user_provider_id = $request->user_provider_id;
        $user_id = $request->user_id;

        if (! $user_provider_id > 0 || ! $user_id > 0) {
            flash('Неверный запрос. Пожалуйста, попробуйте снова.')->error()->important();

            return redirect()->back();
        }

        $user_provider = UserProvider::findOrFail($user_provider_id);

        if ($user_id == $user_provider->user->id) {
            $user_provider->delete();

            flash('Отвязано от пользователя "'.$user_provider->user->name.'"!')->success()->important();

            return redirect()->back();
        }

        flash('Запрос отклонен. Пожалуйста, обратитесь к администратору!')->warning()->important();

        event(new UserUpdated($$this->module_name_singular));

        throw new Exception('Возникла проблема при обновлении пользователя. Пожалуйста, попробуйте снова.');
    }

    /**
     * Resends the email confirmation for a user.
     *
     * @param  int  $id  The ID of the user.
     * @return \Illuminate\Http\RedirectResponse Returns a redirect response.
     *
     * @throws \Illuminate\Http\Client\RequestException If the user is not authorized to resend the email confirmation.
     */
    public function emailConfirmationResend($id)
    {
        $module_action = 'Повторная отправка подтверждения email';

        if (! auth()->user()->can('edit_users')) {
            $id = auth()->user()->id;
        }

        $user = User::where('id', '=', $id)->first();

        if ($user) {
            if ($user->email_verified_at === null) {
                Log::info($user->name.' ('.$user->id.') - Пользователь запросил подтверждение email.');

                // Отправка email зарегистрированному пользователю
                $user->sendEmailVerificationNotification();

                flash('Email отправлен! Пожалуйста, проверьте вашу почту.')->success()->important();

                return redirect()->back();
            }

            Log::info($user->name.' ('.$user->id.') - Пользователь сделал запрос, но email уже подтвержден '.$user->email_verified_at);

            flash($user->name.', Вы уже подтвердили свой email адрес '.$user->email_verified_at->isoFormat('LL'))->success()->important();

            logUserAccess($this->module_title.' '.$module_action);

            return redirect()->back();
        }
    }

     /**
     * Updates the password for a user.
     *
     * @param  int  $id  The ID of the user whose password will be changed.
     * @return \Illuminate\Contracts\View\View The view for the "Change Password" page.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user cannot be found.
     */
    public function changePassword($id)
    {
        $module_action = 'Сменить пароль';

        if (! auth()->user()->can('edit_users')) {
            $id = auth()->user()->id;
        }

        $user = $this->module_model::findOrFail($id);

        logUserAccess("{$this->module_title} {$module_action} {$user->name} ($id)");

        return view(
            "{$this->module_path}.{$this->module_name}.changePassword",
            compact('module_action', 'user')
        );
    }

    /**
     * Updates the password for a user.
     *
     * @param  Request  $request  The request object containing the new password.
     * @param  int  $id  The ID of the user whose password is being updated.
     * @return \Illuminate\Http\RedirectResponse The response object redirecting to the admin module.
     *
     * @throws \Illuminate\Validation\ValidationException If the validation fails.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user with the given ID is not found.
     */
    public function changePasswordUpdate(Request $request, $id)
    {
        $module_action = 'Обновление пароля';

        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        if (! auth()->user()->can('edit_users')) {
            $id = auth()->user()->id;
        }

        $$this->module_name_singular = User::findOrFail($id);

        $request_data = $request->only('password');
        $request_data['password'] = Hash::make($request_data['password']);

        $$this->module_name_singular->update($request_data);

        flash(Str::singular($this->module_title)." успешно обновлен")->success()->important();

        logUserAccess("{$this->module_title} {$module_action} {$$this->module_name_singular->name} ($id)");

        return redirect("admin/{$this->module_name}/{$id}");
    }
}
