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
                        <div class="col-md-2">
                            <select class="form-select filter select2-user" data-column="user_id">
                                <option value="">Все пользователи</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select filter" data-column="status">
                                <option value="">Все статусы</option>
                                <option value="completed">Выполнен</option>
                                <option value="pending">В обработке</option>
                                <option value="failed">Ошибка</option>
                                <option value="refunded">Возвращен</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select filter" data-column="payment_method">
                                <option value="">Все методы</option>
                                <option value="card">Карта</option>
                                <option value="bank_transfer">Банковский перевод</option>
                                <option value="crypto">Криптовалюта</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-2">
                            <input type="number" class="form-control filter" data-column="amount_from" placeholder="Сумма от">
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control filter" data-column="amount_to" placeholder="Сумма до">
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
                                    <th>Пользователь</th>
                                    <th>Сумма</th>
                                    <th>Метод</th>
                                    <th>Статус</th>
                                    <th>Дата</th>
                                    <th width="15%">Действия</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @component('admin.components.modal-datatable', [
        'id' => 'paymentDetailsModal',
        'title' => 'Детали платежа',
        'columns' => [
            'key' => 'Параметр',
            'value' => 'Значение'
        ]
    ])
    @endcomponent
@endsection

@push('after-styles')
    <!-- DataTables Core and Extensions -->
    <link href="{{ asset('vendor/datatable/datatables.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush

@push('after-scripts')
    <script type="module" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/admin/datatable-modal.js') }}"></script>
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
                    url: '{{ route("admin.payments.index_data") }}',
                    data: function(d) {
                        $('.filter').each(function() {
                            d[$(this).data('column')] = $(this).val();
                        });
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'formatted_amount', name: 'amount'},
                    {data: 'payment_method', name: 'payment_method'},
                    {data: 'status_badge', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action'}
                ]
            });

            // Инициализация фильтров
            initFilters(table, {
                select2Selectors: ['.select2-user']
            });
            autoSelectUserFromUrl(table);
        });
    </script>
@endpush
