@extends("admin.layouts.app")

@section("breadcrumbs")
<x-admin.breadcrumbs>
    <x-admin.breadcrumb-item icon="{{ $module_icon }}">
        Настройки
    </x-admin.breadcrumb-item>
    <x-admin.breadcrumb-item type="active">
    {{ $module_title }}
    </x-admin.breadcrumb-item>
</x-admin.breadcrumbs>
@endsection

@push("after-scripts")
<script>
$(function () {
    // Поиск по категориям
    $("#search_cat").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#cats tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Обработка редактирования категории
    $('.edit-category-btn').on('click', function() {
        var row = $(this).closest('tr');
        var id = $(this).data('id');
        var name = row.find('td:first').text();

        $('#categoryName' + id).val(name);
    });

    // Обработка редактирования пола
    $('.edit-gender-btn').on('click', function() {
        var row = $(this).closest('tr');
        var id = $(this).data('id');
        var name = row.find('td:first').text();

        $('#genderName' + id).val(name);
    });

    // Обработка редактирования направления
    $('.edit-layer-btn').on('click', function() {
        var row = $(this).closest('tr');
        var id = $(this).data('id');
        var name = row.find('td:first').text();

        $('#layerName' + id).val(name);
    });
});
</script>
@endpush

@push('after-styles')
<style>
    th:empty {
        background: none !important;
        padding: 0 !important;
    }
</style>
@endpush

@section("content")
<div class="container">
    <div class="row">
        <!-- Категории производств -->
        <div class="col-md-4" id="categories">
            <div class="card mb-4">
                <div class="card-header">
                    Категории производств
                    <a class="btn btn-sm btn-primary float-end" data-coreui-toggle="modal" data-coreui-target="#addCategoryModal"><i class="fas fa-plus"></i></a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="cats">
                        <thead>
                            <tr>
                                <th><input type="text" id="search_cat" placeholder="Поиск..." class="form-control"></th>
                                <th width="30%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (App\Models\Filter::where('filter_key', 'category')->get() as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <a class="btn btn-sm btn-primary mt-1 edit-category-btn"
                                       data-toggle="tooltip"
                                       data-coreui-toggle="modal"
                                       data-coreui-target="#editCategoryModal{{ $category->id }}"
                                       data-id="{{ $category->id }}"
                                       title="Редактировать">
                                        <i class="fas fa-wrench"></i>
                                    </a>
                                    <form action="/admin/sellers_conf/category/delete/{{ $category->id }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger mt-1" title="Удалить" onclick="return confirm('Вы уверены?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Модальное окно редактирования категории -->
                            <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">Редактировать категорию</h5>
                                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="/admin/sellers_conf/category/update/{{ $category->id }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="categoryName{{ $category->id }}" class="form-label">Название</label>
                                                    <input type="text" class="form-control" id="categoryName{{ $category->id }}" name="name" value="{{ $category->name }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Отмена</button>
                                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Модальное окно добавления категории -->
            <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCategoryModalLabel">Добавить категорию</h5>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="/admin/sellers_conf/category/store" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="categoryName" class="form-label">Название</label>
                                    <input type="text" class="form-control" id="categoryName" name="name" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Отмена</button>
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Пол -->
        <div class="col-md-4" id="genders">
            <div class="card mb-4">
                <div class="card-header">
                    Пол
                    <a class="btn btn-sm btn-primary float-end" data-coreui-toggle="modal" data-coreui-target="#addGenderModal"><i class="fas fa-plus"></i></a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="genders">
                        <thead>
                            <tr>
                                <th></th>
                                <th width="30%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (App\Models\Filter::where('filter_key', 'gender')->get() as $gender)
                            <tr>
                                <td>{{ $gender->name }}</td>
                                <td>
                                    <a class="btn btn-sm btn-primary mt-1 edit-gender-btn"
                                       data-toggle="tooltip"
                                       data-coreui-toggle="modal"
                                       data-coreui-target="#editGenderModal{{ $gender->id }}"
                                       data-id="{{ $gender->id }}"
                                       title="Редактировать">
                                        <i class="fas fa-wrench"></i>
                                    </a>
                                    <form action="/admin/sellers_conf/gender/delete/{{ $gender->id }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger mt-1" title="Удалить" onclick="return confirm('Вы уверены?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Модальное окно редактирования пола -->
                            <div class="modal fade" id="editGenderModal{{ $gender->id }}" tabindex="-1" aria-labelledby="editGenderModalLabel{{ $gender->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editGenderModalLabel{{ $gender->id }}">Редактировать пол</h5>
                                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="/admin/sellers_conf/gender/update/{{ $gender->id }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="genderName{{ $gender->id }}" class="form-label">Название</label>
                                                    <input type="text" class="form-control" id="genderName{{ $gender->id }}" name="name" value="{{ $gender->name }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Отмена</button>
                                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Модальное окно добавления пола -->
            <div class="modal fade" id="addGenderModal" tabindex="-1" aria-labelledby="addGenderModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addGenderModalLabel">Добавить пол</h5>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="/admin/sellers_conf/gender/store" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="genderName" class="form-label">Название</label>
                                    <input type="text" class="form-control" id="genderName" name="name" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Отмена</button>
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Масштабы производств -->
        <div class="col-md-4" id="scales">
            <div class="card mb-4">
                <div class="card-header">
                    Масштабы производств
                    <a class="btn btn-sm btn-primary float-end" data-coreui-toggle="modal" data-coreui-target="#addScaleModal"><i class="fas fa-plus"></i></a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="scales">
                        <thead>
                            <tr>
                                <th></th>
                                <th width="30%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (App\Models\Filter::where('filter_key', 'scale')->get() as $scale)
                            <tr>
                                <td>{{ $scale->name }}</td>
                                <td>
                                    <a class="btn btn-sm btn-primary mt-1 edit-scale-btn"
                                       data-toggle="tooltip"
                                       data-coreui-toggle="modal"
                                       data-coreui-target="#editScaleModal{{ $scale->id }}"
                                       data-id="{{ $scale->id }}"
                                       title="Редактировать">
                                        <i class="fas fa-wrench"></i>
                                    </a>
                                    <form action="/admin/sellers_conf/scale/delete/{{ $scale->id }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger mt-1" title="Удалить" onclick="return confirm('Вы уверены?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Модальное окно редактирования масштаба -->
                            <div class="modal fade" id="editScaleModal{{ $scale->id }}" tabindex="-1" aria-labelledby="editScaleModalLabel{{ $scale->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editScaleModalLabel{{ $scale->id }}">Редактировать масштаб</h5>
                                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="/admin/sellers_conf/scale/update/{{ $scale->id }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="scaleName{{ $scale->id }}" class="form-label">Название</label>
                                                    <input type="text" class="form-control" id="scaleName{{ $scale->id }}" name="name" value="{{ $scale->name }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Отмена</button>
                                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Модальное окно добавления масштаба -->
            <div class="modal fade" id="addScaleModal" tabindex="-1" aria-labelledby="addScaleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addScaleModalLabel">Добавить масштаб</h5>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="/admin/sellers_conf/scale/store" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="scaleName" class="form-label">Название</label>
                                    <input type="text" class="form-control" id="scaleName" name="name" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Отмена</button>
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Направления пошива -->
        <div class="col-md-4" id="layers">
            <div class="card mb-4">
                <div class="card-header">
                    Направления пошива
                    <a class="btn btn-sm btn-primary float-end" data-coreui-toggle="modal" data-coreui-target="#addLayerModal"><i class="fas fa-plus"></i></a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="layers">
                        <thead>
                            <tr>
                                <th></th>
                                <th width="30%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (App\Models\Filter::where('filter_key', 'layer')->get() as $layer)
                            <tr>
                                <td>{{ $layer->name }}</td>
                                <td>
                                    <a class="btn btn-sm btn-primary mt-1 edit-layer-btn"
                                       data-toggle="tooltip"
                                       data-coreui-toggle="modal"
                                       data-coreui-target="#editLayerModal{{ $layer->id }}"
                                       data-id="{{ $layer->id }}"
                                       title="Редактировать">
                                        <i class="fas fa-wrench"></i>
                                    </a>
                                    <form action="/admin/sellers_conf/layer/delete/{{ $layer->id }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger mt-1" title="Удалить" onclick="return confirm('Вы уверены?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Модальное окно редактирования направления -->
                            <div class="modal fade" id="editLayerModal{{ $layer->id }}" tabindex="-1" aria-labelledby="editLayerModalLabel{{ $layer->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editLayerModalLabel{{ $layer->id }}">Редактировать направление</h5>
                                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="/admin/sellers_conf/layer/update/{{ $layer->id }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="layerName{{ $layer->id }}" class="form-label">Название</label>
                                                    <input type="text" class="form-control" id="layerName{{ $layer->id }}" name="name" value="{{ $layer->name }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Отмена</button>
                                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Модальное окно добавления направления -->
            <div class="modal fade" id="addLayerModal" tabindex="-1" aria-labelledby="addLayerModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addLayerModalLabel">Добавить направление</h5>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="/admin/sellers_conf/layer/store" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="layerName" class="form-label">Название</label>
                                    <input type="text" class="form-control" id="layerName" name="name" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Отмена</button>
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
