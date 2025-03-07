@extends("admin.layouts.app")

@section("title")
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section("breadcrumbs")
    <x-admin.breadcrumbs>
        <x-admin.breadcrumb-item type="active" icon="{{ $module_icon }}">
            {{ __($module_title) }}
        </x-admin.breadcrumb-item>
    </x-admin.breadcrumbs>
@endsection

@section("content")
    <div class="card">
        <div class="card-body">
            <x-admin.section-header
                :module_name="$module_name"
                :module_title="$module_title"
                :module_icon="$module_icon"
                :module_action="$module_action"
                :create_route="route('admin.announces.create')"
                :filters_block=1
            />

            {{-- Фильтры --}}
            <div class="collapse mb-4" id="filters-collapse">
                <div class="card card-body">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <input type="text" class="form-control filter" data-column="id" placeholder="ID">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select filter select2-user" data-column="user_id">
                                <option value="">Все пользователи</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select filter select2-category" data-column="category_id">
                                <option value="">Все категории</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select filter" data-column="status">
                                <option value="">Все статусы</option>
                                <option value="draft">Черновик</option>
                                <option value="moderation">На модерации</option>
                                <option value="active">Активно</option>
                                <option value="inactive">Неактивно</option>
                                <option value="rejected">Отклонено</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-2">
                            <input type="number" class="form-control filter" data-column="price_min" placeholder="Цена от">
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control filter" data-column="price_max" placeholder="Цена до">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control filter" data-column="date_from" placeholder="Дата от">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control filter" data-column="date_to" placeholder="Дата до">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Заголовок</th>
                                    <th>Пользователь</th>
                                    <th>Категория</th>
                                    <th>Цена</th>
                                    <th>Статус</th>
                                    <th>Дата</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-7">
                    <div class="float-left"></div>
                </div>
                <div class="col-5">
                    <div class="float-end"></div>
                </div>
            </div>
        </div>
    </div>

    @component('admin.components.modal-datatable', [
        'id' => 'responsesModal',
        'title' => 'Отклики на объявление',
        'columns' => [
            'id' => 'ID',
            'user_name' => 'Пользователь',
            'created_at' => 'Дата'
        ]
    ])
    @endcomponent
@endsection

@push("after-scripts")
    <script>
        $(document).ready(function() {
            // Инициализация Select2
            initSelect2('.select2-user', {
                placeholder: 'Выберите пользователя',
                ajax: {
                    url: '{{ route("admin.users.index_list") }}'
                }
            });

            // Инициализация таблицы
            const table = initDataTable({
                ajax: {
                    url: '{{ route("admin.announces.index_data") }}',
                    data: function(d) {
                        $('.filter').each(function() {
                            d[$(this).data('column')] = $(this).val();
                        });
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'content', name: 'content'},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'category_id', name: 'category_id'},
                    {data: 'price', name: 'price'},
                    {data: 'status_label', name: 'status'},
                    {data: 'updated_at', name: 'updated_at'},
                ],
                createdRow: function(row, data) {
                    $(row).attr('data-id', data.id);
                    $(row).attr('data-status', data.status);
                }
            });

            // Инициализация фильтров
            initFilters(table, {
                select2Selectors: ['.select2-user']
            });
            autoSelectUserFromUrl(table);

            // Функция обновления статуса
            function updateStatus(id, status) {
                $.ajax({
                    url: '/admin/announces/' + id + '/status',
                    type: 'PATCH',
                    data: { status: status },
                    success: function() {
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        console.error('Ошибка при обновлении статуса:', xhr);
                        alert('Произошла ошибка при обновлении статуса');
                    }
                });
            }

            // Инициализация контекстного меню
            initContextMenu(table, {
                callback: function(key, options) {
                    const id = $(this).data('id');
                    const status = $(this).data('status');

                    switch(key) {
                        case "edit":
                            window.location.href = '/admin/announces/' + id + '/edit';
                            break;
                        case "responses":
                            // Получаем данные для модального окна
                            const modal = $('#responsesModal');
                            const responsesTable = modal.find('table').DataTable();

                            // Обновляем данные таблицы
                            responsesTable.ajax.url('{{ route("admin.announces.responses", "") }}/' + id).load();

                            // Показываем модальное окно
                            modal.modal('show');
                            break;
                        case "activate":
                            updateStatus(id, 'active');
                            break;
                        case "deactivate":
                            updateStatus(id, 'inactive');
                            break;
                        case "approve":
                            updateStatus(id, 'active');
                            break;
                        case "reject":
                            updateStatus(id, 'rejected');
                            break;
                        case "delete":
                            if (confirm('Вы уверены?')) {
                                $.ajax({
                                    url: '/admin/announces/' + id,
                                    type: 'DELETE',
                                    success: function() {
                                        table.ajax.reload();
                                    },
                                    error: function(xhr) {
                                        console.error('Ошибка при удалении:', xhr);
                                        alert('Произошла ошибка при удалении');
                                    }
                                });
                            }
                            break;
                    }
                },
                items: {
                    "edit": {
                        name: "Редактировать",
                        icon: "fas fa-edit"
                    },
                    "responses": {
                        name: "Отклики",
                        icon: "fas fa-comments"
                    },
                    "sep1": "---------",
                    "status": {
                        name: "Статус",
                        icon: "fas fa-toggle-on",
                        items: {
                            "activate": {
                                name: "Активировать",
                                icon: "fas fa-check",
                                disabled: function(key, opt) {
                                    return $(this).data('status') === 'active';
                                }
                            },
                            "deactivate": {
                                name: "Деактивировать",
                                icon: "fas fa-times",
                                disabled: function(key, opt) {
                                    return $(this).data('status') === 'inactive';
                                }
                            },
                            "approve": {
                                name: "Одобрить",
                                icon: "fas fa-check-circle",
                                disabled: function(key, opt) {
                                    return $(this).data('status') !== 'moderation';
                                }
                            },
                            "reject": {
                                name: "Отклонить",
                                icon: "fas fa-ban",
                                disabled: function(key, opt) {
                                    return $(this).data('status') !== 'moderation';
                                }
                            }
                        }
                    },
                    "sep2": "---------",
                    "delete": {
                        name: "Удалить",
                        icon: "fas fa-trash",
                        className: 'context-menu-item-danger'
                    }
                }
            });

            // Инициализация таблицы откликов в модальном окне
           // Инициализация модальной таблицы откликов
           new DatatableModal({
                modalId: 'responsesModal',
                buttonSelector: '.responses-badge',
                url: '{{ route("admin.announces.responses", "") }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'created_at', name: 'created_at'}
                ]
            });
        });
    </script>
@endpush
