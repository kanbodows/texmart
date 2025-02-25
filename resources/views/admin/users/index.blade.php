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
                            <select class="form-select filter" data-column="status">
                                <option value="">Все статусы</option>
                                <option value="1">Активный</option>
                                <option value="0">Неактивный</option>
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
                                    <th>{{ __("labels.admin.users.fields.name") }}</th>
                                    <th>{{ __("labels.admin.users.fields.email") }}</th>
                                    <th>{{ __("labels.admin.users.fields.status") }}</th>
                                    <th>{{ __("labels.admin.users.fields.roles") }}</th>
                                    <th class="text-end">{{ __("labels.admin.action") }}</th>
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
    <link rel="stylesheet" href="{{ asset("vendor/datatable/datatables.min.css") }}" />
@endpush

@push("after-scripts")
    <!-- DataTables Core and Extensions -->
    <script type="module" src="{{ asset("vendor/datatable/datatables.min.js") }}"></script>

    <script type="module">
        $(document).ready(function() {
            let table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: true,
                responsive: true,
                searching: false,
                pageLength: 50,
                ajax: {
                    url: '{{ route("admin.$module_name.index_data") }}',
                    data: function(d) {
                        $('.filter').each(function() {
                            d[$(this).data('column')] = $(this).val();
                        });
                    }
                },
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.2.2/i18n/ru.json'
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'user_roles',
                        name: 'user_roles',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    },
                ],
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
