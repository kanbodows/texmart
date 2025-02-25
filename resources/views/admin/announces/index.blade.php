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
                :filters_block=1
            />

            {{-- Фильтры --}}
            <div class="collapse mb-4" id="filters-collapse">
                <div class="card card-body">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <input type="text" class="form-control filter" data-column="id" placeholder="ID">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control filter" data-column="name" placeholder="Название">
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
                            <input type="text" class="form-control filter" data-column="phone" placeholder="Телефон">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control filter" data-column="email" placeholder="Email">
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
                            <input type="text" class="form-control filter" data-column="locate" placeholder="Местоположение">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select filter" data-column="check">
                                <option value="">Все статусы</option>
                                <option value="1">Активные</option>
                                <option value="0">Неактивные</option>
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
                        <table class="table-bordered table-hover table" id="datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Категория</th>
                                    <th>Телефон</th>
                                    <th>Email</th>
                                    <th>Цена</th>
                                    <th>Локация</th>
                                    <th>Статус</th>
                                    <th>Обновлено</th>
                                    <th>Действия</th>
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

    <script type="module">
        $(document).ready(function() {
            $('.select2-category').select2({
                placeholder: 'Выберите категорию',
                allowClear: true
            });

            let table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searching: false,
                pageLength: 50,

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
                    {data: 'name', name: 'name'},
                    {data: 'category_id', name: 'category_id'},
                    {data: 'phone', name: 'phone'},
                    {data: 'email', name: 'email'},
                    {data: 'price', name: 'price'},
                    {data: 'locate', name: 'locate'},
                    {data: 'check', name: 'check'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[0, 'desc']],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.2.2/i18n/ru.json'
                }
            });

            // Обработка фильтров
            let timer;
            $('.filter').on('change keyup select2:select select2:unselect', function() {
                clearTimeout(timer);
                timer = setTimeout(function() {
                    table.draw();
                }, 500);
            });

            // Кнопка сброса фильтров
            $('#reset-filters').on('click', function() {
                $('.filter').val('');
                $('.select2-category').val(null).trigger('change');
                table.draw();
            });
        });
    </script>
@endpush
