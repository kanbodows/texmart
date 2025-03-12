@extends("admin.layouts.app")

@section("title")
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section("content")
    <div class="card">
        <div class="card-body">
            <x-admin.section-header
                :module_name="$module_name"
                :module_title="$module_title"
                :module_icon="$module_icon"
                :module_action="$module_action"
                :create_route="route('admin.users.create')"
                :filters_block=1
                :add_button=1
            />

            {{-- Фильтры --}}
            <div class="collapse mb-4" id="filters-collapse">
                <div class="card card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control filter" data-column="id" placeholder="ID">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control filter" data-column="name" placeholder="Имя">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control filter" data-column="email" placeholder="Email">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control filter" data-column="mobile" placeholder="Телефон">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select filter" data-column="status">
                                <option value="">Все статусы</option>
                                @foreach(App\Enums\UserStatus::cases() as $status)
                                    <option value="{{ $status->value }}">{{ $status->name() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select filter" data-column="roles">
                                <option value="">Все роли</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table id="datatable" class="table-bordered table-hover table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Имя</th>
                                    <th>Email</th>
                                    <th>Телефон</th>
                                    <th>Статус</th>
                                    <th>Роли</th>
                                    {{-- <th class="text-end">Действия</th> --}}
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("after-styles")
@endpush

@push("after-scripts")
    <script>
        $(document).ready(function() {
            // Инициализация таблицы
            const table = initDataTable({
                ajax: {
                    url: '{{ route("admin.users.index_data") }}',
                    data: function(d) {
                        $('.filter').each(function() {
                            d[$(this).data('column')] = $(this).val();
                        });
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'status_name', name: 'status_name'},
                    {data: 'user_roles', name: 'user_roles'},
                    // {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                createdRow: function(row, data) {
                    $(row).attr('data-id', data.id);
                    $(row).attr('data-status', data.status);
                    $(row).attr('data-email_verified', data.email_verified_at);
                }
            });

            // Инициализация фильтров
            initFilters(table);

            // Инициализация контекстного меню
            initContextMenu(table, {
                items: {
                    view: {
                        name: "Просмотр",
                        icon: "fas fa-eye",
                        disabled: {{ !auth()->user()->can('user_show') ? 'true' : 'false' }}
                    },
                    payments: {
                        name: "Показать платежи",
                        icon: "fas fa-money-bill",
                        callback: function(key, opt) {
                            const id = $(this).data('id');
                            window.open('/admin/payments?user_id=' + id, '_blank');
                        }
                    },
                    responses: {
                        name: "Показать отклики",
                        icon: "fas fa-comments",
                        callback: function(key, opt) {
                            const id = $(this).data('id');
                            window.open('/admin/responses?user_id=' + id, '_blank');
                        }
                    },
                    edit: {
                        name: "Редактировать",
                        icon: "fas fa-edit",
                        disabled: {{ !auth()->user()->can('user_edit') ? 'true' : 'false' }}
                    },
                    "sep1": "---------",
                    password: {
                        name: "Сменить пароль",
                        icon: "fas fa-key",
                        callback: function(key, opt) {
                            const id = $(this).data('id');
                            window.open('/admin/users/' + id + '/change-password', '_blank');
                        }
                    },
                    block: {
                        name: "Заблокировать",
                        icon: "fas fa-ban",
                        disabled: function() {
                            const id = $(this).data('id');
                            const status = $(this).data('status');
                            return id === 1 || status == '{{ App\Enums\UserStatus::BLOCKED }}';
                        },
                        callback: function(key, opt) {
                            const id = $(this).data('id');
                            updateUser(id, { status: '{{ App\Enums\UserStatus::BLOCKED }}' });
                        }
                    },
                    unblock: {
                        name: "Разблокировать",
                        icon: "fas fa-check",
                        disabled: function() {
                            return $(this).data('status') != '{{ App\Enums\UserStatus::BLOCKED }}';
                        },
                        callback: function(key, opt) {
                            const id = $(this).data('id');
                            updateUser(id, { status: '{{ App\Enums\UserStatus::ACTIVE }}' });
                        }
                    },
                    "sep2": "---------",
                    delete: {
                        disabled: function() {
                            return $(this).data('id') === 1;
                        }
                    },
                    "sep3": "---------",
                    resendEmail: {
                        name: "Отправить письмо подтверждения",
                        icon: "fas fa-envelope",
                        disabled: function() {
                            return $(this).data('email_verified') !== null;
                        },
                        callback: function(key, opt) {
                            const id = $(this).data('id');
                            window.location.href = '/admin/users/' + id + '/email-confirmation-resend';
                        }
                    }
                }
            });

            // Универсальная функция обновления пользователя
            function updateUser(id, data) {
                console.log('Updating user:', id, 'with data:', data); // Для отладки

                $.ajax({
                    url: '{{ route("admin.users.updateAjax", ["user" => "__ID__"]) }}'.replace('__ID__', id),
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ...data
                    },
                    success: function(response) {
                        console.log('Server response:', response); // Для отладки
                        if (response.success) {
                            table.ajax.reload();
                        } else {
                            alert(response.message || 'Произошла ошибка при обновлении пользователя');
                        }
                    },
                    error: function(xhr) {
                        console.error('Ошибка при обновлении пользователя:', xhr);
                        alert(xhr.responseJSON?.message || 'Произошла ошибка при обновлении пользователя');
                    }
                });
            }
        });
    </script>
@endpush
