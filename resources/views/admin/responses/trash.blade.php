@extends('admin.layouts.app')

@section('title')
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <x-admin.section-header :module_name="$module_name" :module_title="$module_title" :module_icon="$module_icon" :module_action="$module_action" />

            <div class="row mt-4">
                <div class="col">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Объявление</th>
                                <th>Удален</th>
                                <th class="text-end">Действия</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($$module_name as $module_name_singular)
                            <tr>
                                <td>{{ $module_name_singular->id }}</td>
                                <td>
                                    @if($module_name_singular->user)
                                        <a href="{{ route('admin.users.show', $module_name_singular->user) }}">
                                            {{ $module_name_singular->user->name }}
                                        </a>
                                    @else
                                        Удален
                                    @endif
                                </td>
                                <td>
                                    @if($module_name_singular->announce)
                                        <a href="{{ route('admin.announces.show', $module_name_singular->announce) }}">
                                            {{ $module_name_singular->announce->title }}
                                        </a>
                                    @else
                                        Удалено
                                    @endif
                                </td>
                                <td>{{ $module_name_singular->deleted_at->diffForHumans() }}</td>
                                <td class="text-end">
                                    <a href="{{ route("admin.$module_name.restore", $module_name_singular->id) }}"
                                        class="btn btn-warning btn-sm"
                                        data-method="PATCH"
                                        data-token="{{ csrf_token() }}"
                                        data-toggle="tooltip"
                                        title="Восстановить {{ $module_name_singular->name }}"
                                        data-confirm="Вы уверены?">
                                        <i class="fas fa-undo"></i> Восстановить
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-7">
            <div class="float-left">
                {!! $$module_name->total() !!} {{ __('labels.backend.total') }}
            </div>
        </div>
        <div class="col-5">
            <div class="float-end">
                {!! $$module_name->render() !!}
            </div>
        </div>
    </div>
@endsection
