@extends('admin.layouts.app')

@section('title')
    {{ __($module_action) }} {{ __($module_title) }}
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
                :add_button=0
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
                            <select class="form-select filter select2-announce" data-column="announce_id">
                                <option value="">Все объявления</option>
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
                        <table class="table-bordered table-hover table" id="datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Пользователь</th>
                                    <th>Объявление</th>
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
    <script src="{{ asset('js/admin/context-menu.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Инициализация Select2
            initSelect2('.select2-user', {
                placeholder: 'Выберите пользователя',
                ajax: {
                    url: '{{ route("admin.users.index_list") }}'
                }
            });

            initSelect2('.select2-announce', {
                placeholder: 'Выберите объявление',
                ajax: {
                    url: '{{ route("admin.announces.index_list") }}'
                }
            });

            // Инициализация таблицы
            const table = initDataTable({
                ajax: {
                    url: '{{ route("admin.responses.index_data") }}',
                    data: function(d) {
                        $('.filter').each(function() {
                            d[$(this).data('column')] = $(this).val();
                        });
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'announce_title', name: 'announce_title'},
                    {data: 'created_at', name: 'created_at'}
                ],
                createdRow: function(row, data) {
                    // Добавляем data-id для контекстного меню
                    $(row).attr('data-id', data.id);
                }
            });

            // Инициализация фильтров
            initFilters(table, {
                select2Selectors: ['.select2-user', '.select2-announce']
            });
            autoSelectUserFromUrl(table);

            initContextMenu(table, {
                items: {
                    view: {},
                    "sep1": "---------",
                    delete: {},
                },
            });
        });
    </script>
@endpush
