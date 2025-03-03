@extends('admin.layouts.app')

@section('title')
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section('breadcrumbs')
    <x-admin.breadcrumbs>
        <x-admin.breadcrumb-item href="{{ route('admin.feedbacks.index') }}" icon="{{ $module_icon }}">
            {{ __($module_title) }}
        </x-admin.breadcrumb-item>
        <x-admin.breadcrumb-item type="active">{{ __($module_action) }}</x-admin.breadcrumb-item>
    </x-admin.breadcrumbs>
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
                                <td>{{ $feedback->id }}</td>
                            </tr>

                            <tr>
                                <th>Пользователь:</th>
                                <td>
                                    @if($feedback->user)
                                        <a href="{{ route('admin.users.show', $feedback->user) }}">
                                            {{ $feedback->user->name }}
                                        </a>
                                    @else
                                        Удален
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Производитель:</th>
                                <td>
                                    @if($feedback->manufacturer)
                                        <a href="{{ route('admin.users.show', $feedback->manufacturer) }}">
                                            {{ $feedback->manufacturer->name }}
                                        </a>
                                    @else
                                        Удален
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Оценка:</th>
                                <td>
                                    <span class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $feedback->rating)
                                                ★
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <th>Отзыв:</th>
                                <td>{{ $feedback->feedback }}</td>
                            </tr>

                            <tr>
                                <th>Дата создания:</th>
                                <td>{{ $feedback->created_at }}</td>
                            </tr>

                            <tr>
                                <th>Дата обновления:</th>
                                <td>{{ $feedback->updated_at }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <a href="{{ route('admin.feedbacks.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Назад
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
