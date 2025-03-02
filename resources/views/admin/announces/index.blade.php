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
                                <option value="active">Активные</option>
                                <option value="inactive">Неактивные</option>
                                <option value="moderation">На модерации</option>
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
                        <table class="table-bordered table-hover table" id="datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Категория</th>
                                    <th>Цена</th>
                                    <th>Статус</th>
                                    <th>Обновлено</th>
                                    <th width="15%">Действия</th>
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

@push("after-styles")
    <!-- DataTables Core and Extensions -->
    <link href="{{ asset("vendor/datatable/datatables.min.css") }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush

@push("after-scripts")
    <!-- DataTables Core and Extensions -->
    <script type="module" src="{{ asset("vendor/datatable/datatables.min.js") }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/admin/common.js') }}"></script>

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
                    {data: 'title', name: 'title'},
                    {data: 'category_id', name: 'category_id'},
                    {data: 'price', name: 'price'},
                    {data: 'check', name: 'check'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'action', name: 'action'}
                ]
            });

            // Инициализация фильтров
            initFilters(table);
            autoSelectUserFromUrl(table);

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
