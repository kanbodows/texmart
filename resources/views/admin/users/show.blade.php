@extends("admin.layouts.app")

@section("title")
    {{ $$module_name_singular->name }} - {{ $$module_name_singular->username }}
@endsection

@section("content")
    <x-admin.layouts.show :data="$user">
        <x-admin.section-header>
            <i class="{{ $module_icon }}"></i>
            {{ $$module_name_singular->name }}
            <small class="text-muted">Просмотр пользователя</small>

            <x-slot name="toolbar">
                <x-admin.buttons.return-back :small="true" />
                <a
                    class="btn btn-primary btn-sm m-1"
                    data-toggle="tooltip"
                    href="{{ route("admin.users.index") }}"
                    title="Список"
                >
                    <i class="fas fa-list"></i>
                    Список
                </a>
                <x-buttons.edit
                    title="Редактировать пользователя"
                    route='{!! route("admin.$module_name.edit", $$module_name_singular) !!}'
                    :small="true"
                />
            </x-slot>
        </x-admin.section-header>

        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table-bordered table-hover table">
                        <tr>
                            <th>Аватар</th>
                            <td>
                                <img
                                    class="user-profile-image img-fluid img-thumbnail"
                                    src="{{ asset($$module_name_singular->avatar) }}"
                                    style="max-height: 200px; max-width: 200px"
                                />
                            </td>
                        </tr>

                        @php
                            $fields_array = [
                                ["name" => "username", "label" => "Логин"],
                                ["name" => "name", "label" => "Имя"],
                                ["name" => "email", "label" => "Email"],
                                ["name" => "mobile", "label" => "Телефон"],
                                ["name" => "gender", "label" => "Пол"],
                                ["name" => "date_of_birth", "label" => "Дата рождения"],
                                ["name" => "address", "label" => "Адрес"],
                                ["name" => "bio", "label" => "О себе"],
                                ["name" => "last_ip", "label" => "Последний IP"],
                                ["name" => "login_count", "label" => "Количество входов"],
                                ["name" => "last_login", "label" => "Последний вход"],
                            ];
                        @endphp

                        @foreach ($fields_array as $item)
                            <tr>
                                <th>{{ $item['label'] }}</th>
                                <td>{{ $user->{$item['name']} }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <th>Пароль</th>
                            <td>
                                <a
                                    class="btn btn-outline-primary btn-sm"
                                    href="{{ route("admin.users.changePassword", $user->id) }}"
                                >
                                    Изменить пароль
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <th>Соц. сети</th>
                            <td>
                                <ul class="list-unstyled">
                                    @foreach ($user->providers as $provider)
                                        <li>
                                            <i class="fab fa-{{ $provider->provider }}"></i>
                                            {{ $provider->provider }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>

                        <tr>
                            <th>Статус</th>
                            <td>{!! $user->status_label !!}</td>
                        </tr>

                        <tr>
                            <th>Подтверждение</th>
                            <td>
                                {!! $user->confirmed_label !!}
                                @if ($user->email_verified_at == null)
                                        <a
                                            class="btn btn-primary btn-sm mt-1"
                                            data-toggle="tooltip"
                                            href="{{ route("admin.users.emailConfirmationResend", $user->id) }}"
                                            title="Отправить письмо подтверждения"
                                        >
                                            <i class="fas fa-envelope"></i>
                                            Отправить напоминание
                                        </a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Роли</th>
                            <td>
                                @if ($user->getRoleNames()->count() > 0)
                                    <ul>
                                        @foreach ($user->getRoleNames() as $role)
                                            <li>{{ ucwords($role) }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Разрешения</th>
                            <td>
                                @if ($user->getAllPermissions()->count() > 0)
                                    <ul>
                                        @foreach ($user->getAllPermissions() as $permission)
                                            <li>{{ $permission->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Дата создания</th>
                            <td>
                                {{ $user->created_at }} Пользователь:{{ $user->created_by }}
                                <br />
                                <small>({{ $user->created_at->diffForHumans() }})</small>
                            </td>
                        </tr>

                        <tr>
                            <th>Дата обновления</th>
                            <td>
                                {{ $user->updated_at }} Пользователь:{{ $user->updated_by }}
                                <br />
                                <small>({{ $user->updated_at->diffForHumans() }})</small>
                            </td>
                        </tr>

                        <tr>
                            <th>Дата удаления</th>
                            <td>
                                @if ($user->deleted_at != null)
                                        {{ $user->deleted_at }} Пользователь:{{ $user->deleted_by }}
                                        <br />
                                        <small>({{ $user->deleted_at->diffForHumans() }})</small>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="py-4 text-end">
                    @if ($user->status != 2)
                        <a
                            class="btn btn-danger mt-1"
                            data-method="PATCH"
                            data-token="{{ csrf_token() }}"
                            data-toggle="tooltip"
                            data-confirm="Вы уверены?"
                            href="{{ route("admin.users.block", $user) }}"
                            title="Заблокировать"
                        >
                            <i class="fas fa-ban"></i>
                            Заблокировать
                        </a>
                    @endif

                    @if ($user->status == 2)
                        <a
                            class="btn btn-info mt-1"
                            data-method="PATCH"
                            data-token="{{ csrf_token() }}"
                            data-toggle="tooltip"
                            data-confirm="Вы уверены?"
                            href="{{ route("admin.users.unblock", $user) }}"
                            title="Разблокировать"
                        >
                            <i class="fas fa-check"></i>
                            Разблокировать
                        </a>
                    @endif

                    <a
                        class="btn btn-danger mt-1"
                        data-method="DELETE"
                        data-token="{{ csrf_token() }}"
                        data-toggle="tooltip"
                        data-confirm="Вы уверены?"
                        href="{{ route("admin.users.destroy", $user) }}"
                        title="Удалить"
                    >
                        <i class="fas fa-trash-alt"></i>
                        Удалить
                    </a>
                    @if ($user->email_verified_at == null)
                        <a
                            class="btn btn-primary mt-1"
                            data-toggle="tooltip"
                            href="{{ route("admin.users.emailConfirmationResend", $user->id) }}"
                            title="Отправить письмо подтверждения"
                        >
                            <i class="fas fa-envelope"></i>
                            Подтверждение Email
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </x-admin.layouts.show>
@endsection
