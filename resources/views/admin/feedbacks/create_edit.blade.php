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
            <x-admin.section-header :page_title="__($module_action)" />

            <hr>

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
                        </table>
                    </div>

                    <form action="{{ isset($feedback) ? route('admin.feedbacks.update', $feedback->id) : route('admin.feedbacks.store') }}"
                        method="POST">
                        @csrf
                        @if(isset($feedback))
                            @method('PUT')
                        @endif

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="feedback" class="form-label">Описание <span class="text-danger">*</span></label>
                                <textarea name="feedback"
                                          id="feedback"
                                          class="form-control @error('feedback') is-invalid @enderror"
                                          rows="5"
                                          required>{{ old('feedback', $feedback->feedback ?? '') }}</textarea>
                                @error('feedback')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.feedbacks.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Назад
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    {{ isset($feedback) ? 'Обновить' : 'Создать' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-styles')
@endpush

@push('after-scripts')
@endpush
