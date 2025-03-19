@extends("admin.layouts.app")

@section("content")
    <div class="card">
        <div class="card-body">
            <x-admin.section-header
                :module_name="$module_name"
                :module_title="$module_title"
                :module_icon="$module_icon"
                :module_action="$module_action"
                :create_route="route('admin.ads.create')"
                :filters_block=1
                :add_button=1
            />

            {{-- Фильтры --}}
            <div class="collapse mb-4" id="filters-collapse">
                <div class="card card-body">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <input type="text" class="form-control filter" data-column="id" placeholder="ID">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select filter" data-column="status">
                                <option value="">Все статусы</option>
                                <option value="1">Активные</option>
                                <option value="0">Неактивные</option>
                            </select>
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
                                    <th>Порядок</th>
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
@endsection

@push("after-scripts")
    <script>
        $(document).ready(function() {

            // Инициализация таблицы
            const table = initDataTable({
                ajax: {
                    url: '{{ route("admin.ads.index_data") }}',
                    data: function(d) {
                        $('.filter').each(function() {
                            d[$(this).data('column')] = $(this).val();
                        });
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'order', name: 'order'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                ],
                createdRow: function(row, data) {
                    $(row).attr('data-id', data.id);
                    $(row).attr('data-status', data.status);
                }
            });

            // Инициализация контекстного меню
            initContextMenu(table, {
                items: {
                    edit: {},
                    "sep1": "---------",
                    status: {
                        name: "Статус",
                        icon: "fas fa-toggle-on",
                        items: {
                            activate: {
                                name: "Активировать",
                                icon: "fas fa-check",
                                disabled: function(key, opt) {
                                    return $(this).data('status') === '1';
                                }
                            },
                            deactivate: {
                                name: "Деактивировать",
                                icon: "fas fa-times",
                                disabled: function(key, opt) {
                                    return $(this).data('status') === '0';
                                }
                            }
                        }
                    },
                    "sep2": "---------",
                    delete: {}
                },
                callback: function(key, options) {
                    const id = $(this).data('id');

                    if (['edit', 'delete'].includes(key)) {
                        defaultCallbacks[key](id, table);
                        return;
                    }

                    switch(key) {
                        case "activate":
                            updateModelFields('ads', id, { status: 1 }, table, 'Баннер активирован');
                            break;
                        case "deactivate":
                            updateModelFields('ads', id, { status: 0 }, table, 'Баннер деактивирован');
                            break;
                    }
                }
            });
        });
    </script>
@endpush
