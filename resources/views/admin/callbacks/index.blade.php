@extends("admin.layouts.app")

@section("content")
    <div class="card">
        <div class="card-body">
            <x-admin.section-header
                :module_name="$module_name"
                :module_title="$module_title"
                :module_icon="$module_icon"
                :module_action="$module_action"
                :filters_block=1
            />

            {{-- Фильтры --}}
            <div class="collapse mb-4" id="filters-collapse">
                <div class="card card-body">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <input type="text" class="form-control filter" data-column="name" placeholder="Имя">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control filter" data-column="phone" placeholder="Телефон">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control filter" data-column="email" placeholder="Email">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select filter" data-column="status">
                                <option value="">Все статусы</option>
                                @foreach(\App\Enums\CallbackStatus::cases() as $status)
                                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                @endforeach
                            </select>
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
                                    <th>Имя</th>
                                    <th>Телефон</th>
                                    <th>Email</th>
                                    <th>Комментарий</th>
                                    <th>Статус</th>
                                    <th>Обновил</th>
                                    <th>Дата</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("after-scripts")
    <script>
        $(document).ready(function() {
            // Инициализация таблицы
            const table = initDataTable({
                ajax: {
                    url: '{{ route("admin.callbacks.index_data") }}',
                    data: function(d) {
                        $('.filter').each(function() {
                            d[$(this).data('column')] = $(this).val();
                        });
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'phone', name: 'phone'},
                    {data: 'email', name: 'email'},
                    {data: 'comment', name: 'comment'},
                    {data: 'status', name: 'status'},
                    {data: 'updater', name: 'updater', orderable: false},
                    {data: 'created_at', name: 'created_at'},
                ],
                createdRow: function(row, data) {
                    $(row).attr('data-id', data.id);
                    $(row).attr('data-status', data.status);
                }
            });

            // Инициализация фильтров
            initFilters(table);

            // Инициализация контекстного меню
            initContextMenu(table, {
                items: {
                    edit: {},
                    status: {
                        name: "Статус",
                        icon: "fas fa-toggle-on",
                        items: {
                            new: {
                                name: "Новый",
                                icon: "fas fa-star",
                                disabled: function(key, opt) {
                                    return $(this).data('status') === 'new';
                                }
                            },
                            in_progress: {
                                name: "В обработке",
                                icon: "fas fa-spinner",
                                disabled: function(key, opt) {
                                    return $(this).data('status') === 'in_progress';
                                }
                            },
                            completed: {
                                name: "Завершен",
                                icon: "fas fa-check",
                                disabled: function(key, opt) {
                                    return $(this).data('status') === 'completed';
                                }
                            },
                            canceled: {
                                name: "Отменен",
                                icon: "fas fa-times",
                                disabled: function(key, opt) {
                                    return $(this).data('status') === 'canceled';
                                }
                            }
                        }
                    },
                    "sep1": "---------",
                    delete: {}
                },
                callback: function(key, options) {
                    const id = $(this).data('id');

                    if (['delete', 'edit'].includes(key)) {
                        defaultCallbacks[key](id, table);
                        return;
                    }

                    if (['new', 'in_progress', 'completed', 'canceled'].includes(key)) {
                        updateModelFields('callbacks', id, { status: key }, table, 'Статус обновлен');
                    }
                }
            });
        });
    </script>
@endpush
