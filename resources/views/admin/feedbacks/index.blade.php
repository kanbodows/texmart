@extends('admin.layouts.app')

@section('title')
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section('breadcrumbs')
    <x-admin.breadcrumbs>
        <x-admin.breadcrumb-item type="active" icon="{{ $module_icon }}">
            {{ __($module_title) }}
        </x-admin.breadcrumb-item>
    </x-admin.breadcrumbs>
@endsection

@section('content')
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
                        <div class="col-md-3">
                            <select class="form-select filter select2-user" data-column="user_id">
                                <option value="">Все пользователи</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select filter select2-manufacturer" data-column="manufacture_user_id">
                                <option value="">Все производители</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select filter" data-column="rating">
                                <option value="">Все оценки</option>
                                <option value="1">1 звезда</option>
                                <option value="2">2 звезды</option>
                                <option value="3">3 звезды</option>
                                <option value="4">4 звезды</option>
                                <option value="5">5 звезд</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
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
                        <table class="table table-hover" id="datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Пользователь</th>
                                    <th>Производитель</th>
                                    <th>Оценка</th>
                                    <th>Отзыв</th>
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

@push('after-styles')
@endpush

@push('after-scripts')
    <script>
        $(document).ready(function() {
            // Инициализация Select2
            initSelect2('.select2-user', {
                placeholder: 'Выберите пользователя',
                ajax: {
                    url: '{{ route("admin.users.index_list") }}'
                }
            });

            initSelect2('.select2-manufacturer', {
                placeholder: 'Выберите производителя',
                ajax: {
                    url: '{{ route("admin.users.index_list") }}',
                    data: function(params) {
                        return {
                            ...params,
                            role: 'manufacturer'
                        };
                    }
                }
            });

            // Инициализация таблицы
            const table = initDataTable({
                ajax: {
                    url: '{{ route("admin.feedbacks.index_data") }}',
                    data: function(d) {
                        $('.filter').each(function() {
                            d[$(this).data('column')] = $(this).val();
                        });
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'manufacturer_name', name: 'manufacturer_name'},
                    {data: 'rating_stars', name: 'rating'},
                    {data: 'feedback', name: 'feedback'},
                    {data: 'created_at', name: 'created_at'},
                ],
                createdRow: function(row, data) {
                    $(row).attr('data-id', data.id);
                }
            });

            // Инициализация фильтров
            initFilters(table, {
                select2Selectors: ['.select2-user', '.select2-manufacturer']
            });
            autoSelectUserFromUrl(table);

            // Контекстное меню
            $.contextMenu({
                selector: '#datatable tbody tr',
                callback: function (key, options) {
                    const id = $(this).data('id');
                    switch(key) {
                        case "view":
                            window.location.href = '{{ route("admin.feedbacks.show", "") }}/' + id;
                            break;
                        case "delete":
                            if (confirm('Вы уверены?')) {
                                $.ajax({
                                    url: '{{ route("admin.feedbacks.destroy", "") }}/' + id,
                                    type: 'DELETE',
                                    data: {
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function() {
                                        table.ajax.reload();
                                    }
                                });
                            }
                            break;
                    }
                },
                items: {
                    "view": {
                        name: "Просмотр",
                        icon: "fas fa-eye"
                    },
                    "sep1": "---------",
                    "delete": {
                        name: "Удалить",
                        icon: "fas fa-trash",
                        className: 'context-menu-item-danger'
                    }
                }
            });

            // Подсветка выбранной строки
            $('#datatable tbody').on('contextmenu', 'tr', function() {
                $(this).siblings().removeClass('selected-row');
                $(this).addClass('selected-row');
            });

            // Снятие подсветки при клике вне таблицы
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#datatable').length) {
                    $('#datatable tbody tr').removeClass('selected-row');
                }
            });
        });
    </script>
@endpush
