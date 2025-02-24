@props([
    "data" => "",
    "module_name",
    "module_path",
    "module_title" => "",
    "module_icon" => "",
    "module_action" => "Корзина",
])
<div class="card">
    @if ($slot != "")
        <div class="card-body">
            {{ $slot }}
        </div>
    @else
        <div class="card-body">
            <x-admin.section-header>
                <i class="{{ $module_icon }}"></i>
                {{ __($module_title) }}
                <small class="text-muted">{{ __($module_action) }}</small>

                <x-slot name="toolbar">
                    <x-admin.buttons.return-back :small="true" />
                    <a
                        class="btn btn-secondary btn-sm"
                        data-toggle="tooltip"
                        href="{{ route("admin.$module_name.index") }}"
                        title="Вернуться к списку {{ __(ucwords($module_name)) }}"
                    >
                        <i class="fas fa-list"></i>
                        Список
                    </a>
                </x-slot>
            </x-admin.section-header>

            <div class="row mt-4">
                <div class="col-12">
                    @if (count($data) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Название</th>
                                        <th>Обновлено</th>
                                        <th>Кем создано</th>
                                        <th class="text-end">Действия</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($data as $row)
                                        <tr>
                                            <td>
                                                {{ $row->id }}
                                            </td>
                                            <td>
                                                <strong>
                                                    {{ $row->name }}
                                                </strong>
                                            </td>
                                            <td>
                                                {{ $row->updated_at->format('d.m.Y H:i') }}
                                            </td>
                                            <td>
                                                {{ $row->created_by }}
                                            </td>
                                            <td class="text-end">
                                                <a
                                                    class="btn btn-warning btn-sm"
                                                    data-method="PATCH"
                                                    data-token="{{ csrf_token() }}"
                                                    data-toggle="tooltip"
                                                    href="{{ route("admin.$module_name.restore", $row) }}"
                                                    title="Восстановить"
                                                >
                                                    <i class="fas fa-undo"></i>
                                                    Восстановить
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <p>
                                В корзине нет записей!
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="card-footer">
        @if (! empty($data))
            <div class="row">
                <div class="col-12 col-sm-7">
                    <div class="float-start">
                        <small>
                            Всего
                            {{ $data->total() }} {{ __(ucwords($module_name)) }}
                        </small>
                    </div>
                </div>
                <div class="col-12 col-sm-5">
                    <div class="float-end">
                        {!! $data->render() !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
