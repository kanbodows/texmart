@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <x-admin.section-header
            :module_name="$module_name"
            :module_title="$module_title"
            :module_icon="$module_icon"
            :module_action="$module_action"
            :filters_blocks=1
            :add_button=1
        />

        <div class="row mt-4">
            <div class="col">
                <table class="table table-hover table-bordered" id="datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Заголовок</th>
                            <th>Ссылка</th>
                            <th>Порядок</th>
                            <th>Статус</th>
                            <th>Обновил</th>
                            <th>Последнее обновление</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
<script>
    $(document).ready(function() {
        const table = initDataTable({
            order: [[3, 'asc']],
            ajax: {
                url: '{{ route("admin.pages.index_data") }}',
                data: function(d) {
                    $('.filter').each(function() {
                        d[$(this).data('column')] = $(this).val();
                    });
                }
            },
            createdRow: function(row, data) {
                $(row).attr('data-id', data.id);
                $(row).attr('data-status', data.status);
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'title', name: 'title'},
                {data: 'slug', name: 'slug'},
                {data: 'order', name: 'order'},
                {data: 'status', name: 'status'},
                {data: 'updater_name', name: 'updater_name'},
                {data: 'updated_at', name: 'updated_at',
                    render: {
                        _: 'display',
                        sort: 'timestamp'
                    }
                }
            ]
        });

        initContextMenu(table, {
            items: {
                edit: { },
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
                "sep1": "---------",
                delete: {}
            },
            callback: function(key, options) {
                const id = $(this).data('id');

                if (['delete', 'edit'].includes(key)) {
                    defaultCallbacks[key](id, table);
                    return;
                }

                if (key === 'activate') {
                    updateModelFields('pages', id, { status: 1 }, table);
                }
                if (key === 'deactivate') {
                    updateModelFields('pages', id, { status: 0 }, table);
                }
            }
        });
    });
</script>
@endpush
