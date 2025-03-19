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
                :add_button=1
            />

            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table-bordered table-hover table" id="datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>
                                        Изображение
                                    </th>
                                    <th>
                                       Заголовок
                                    </th>
                                    <th>
                                       Обновлено
                                    </th>
                                    <th class="text-end">
                                       Действия
                                    </th>
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

@push("after-scripts")
    <script type="module">
        $(document).ready(function() {
            const table = initDataTable({
                ajax: '{{ route("admin.$module_name.index_data") }}',
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'image',
                        name: 'image',
                        render: function(data, type, row) {
                            return `<img src="${data}" alt="Изображение новости" class="img-fluid" style="max-height: 50px;">`;
                        },
                        orderable: false,
                    },
                    {
                        data: 'title',
                        name: 'title',
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    },
                ],
            });
        });
    </script>
@endpush
