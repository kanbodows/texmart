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
                                @foreach(App\Enums\AnnounceStatus::cases() as $status)
                                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                @endforeach
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

            // Инициализация контекстного меню
            initContextMenu(table, {
                items: {
                    edit: {},
                    responses: {
                        name: "Отклики",
                        icon: "fas fa-comments"
                    },
                    "sep1": "---------",
                    status: {
                        name: "Статус",
                        icon: "fas fa-toggle-on",
                        items: {
                            activate: {
                                name: "{{ App\Enums\AnnounceStatus::ACTIVE->label() }}",
                                icon: "fas fa-check",
                                disabled: function(key, opt) {
                                    return $(this).data('status') === '{{ App\Enums\AnnounceStatus::ACTIVE->value }}';
                                }
                            },
                            deactivate: {
                                name: "{{ App\Enums\AnnounceStatus::INACTIVE->label() }}",
                                icon: "fas fa-times",
                                disabled: function(key, opt) {
                                    return $(this).data('status') === '{{ App\Enums\AnnounceStatus::INACTIVE->value }}';
                                }
                            },
                            reject: {
                                name: "{{ App\Enums\AnnounceStatus::REJECTED->label() }}",
                                icon: "fas fa-ban",
                                disabled: function(key, opt) {
                                    return $(this).data('status') === '{{ App\Enums\AnnounceStatus::REJECTED->value }}';
                                }
                            }
                        }
                    },
                    "sep2": "---------",
                    delete: {}
                },
                callback: function(key, options) {
                    const id = $(this).data('id');
                    const status = $(this).data('status');

                    // Используем дефолтные колбеки для стандартных действий
                    if (['view', 'edit', 'delete'].includes(key)) {
                        defaultCallbacks[key](id, table);
                        return;
                    }

                    // Кастомные действия
                    switch(key) {
                        case "responses":
                            const modal = $('#responsesModal');
                            const responsesTable = modal.find('table').DataTable();
                            responsesTable.ajax.url('{{ route("admin.announces.responses", "") }}/' + id).load();
                            modal.modal('show');
                            break;
                        case "activate":
                            updateStatus(id, '{{ App\Enums\AnnounceStatus::ACTIVE->value }}');
                            break;
                        case "deactivate":
                            updateStatus(id, '{{ App\Enums\AnnounceStatus::INACTIVE->value }}');
                            break;
                        case "reject":
                            updateStatus(id, '{{ App\Enums\AnnounceStatus::REJECTED->value }}');
                            break;
                    }
                }
            });

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
