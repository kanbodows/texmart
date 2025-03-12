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
            />

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
                                    <th>Дата удаления</th>
                                    <th>Действия</th>
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
            const table = initDataTable({
                ajax: {
                    url: '{{ route("admin.feedbacks.index_data") }}',
                    data: {
                        trashed: true
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'manufacturer_name', name: 'manufacturer_name'},
                    {data: 'rating_stars', name: 'rating'},
                    {data: 'feedback', name: 'feedback'},
                    {data: 'deleted_at', name: 'deleted_at'},
                    {data: 'action', name: 'action'}
                ]
            });
        });
    </script>
@endpush
