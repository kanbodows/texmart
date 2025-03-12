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
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <th>ID:</th>
                                <td>{{ $response->id }}</td>
                            </tr>

                            <tr>
                                <th>Пользователь:</th>
                                <td>
                                    @if($response->user)
                                        <a href="{{ route('admin.users.show', $response->user) }}">
                                            {{ $response->user->name }}
                                        </a>
                                    @else
                                        Удален
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Объявление:</th>
                                <td>
                                    @if($response->announce)
                                        <a href="{{ route('admin.announces.show', $response->announce) }}">
                                            {{ $response->announce->title }}
                                        </a>
                                    @else
                                        Удалено
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Дата создания:</th>
                                <td>{{ $response->created_at }}</td>
                            </tr>

                            <tr>
                                <th>Дата обновления:</th>
                                <td>{{ $response->updated_at }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <a href="{{ route('admin.responses.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Назад
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
