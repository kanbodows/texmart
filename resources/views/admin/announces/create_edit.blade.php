@extends("admin.layouts.app")

@section("title")
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section("breadcrumbs")
    <x-admin.breadcrumbs>
        <x-admin.breadcrumb-item route='{{ route("admin.$module_name.index") }}' icon="{{ $module_icon }}">
            {{ __($module_title) }}
        </x-admin.breadcrumb-item>
        <x-admin.breadcrumb-item type="active">{{ __($module_action) }}</x-admin.breadcrumb-item>
    </x-admin.breadcrumbs>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ isset($announce) ? 'Редактировать' : 'Создать' }} объявление</h4>
                </div>

                <form action="{{ isset($announce) ? route('admin.announces.update', $announce->id) : route('admin.announces.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @if(isset($announce))
                        @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="row g-3">
                            {{-- Название --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Название <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $announce->name ?? '') }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Категория --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id" class="form-label">Категория <span class="text-danger">*</span></label>
                                    <select name="category_id"
                                            id="category_id"
                                            class="form-select @error('category_id') is-invalid @enderror"
                                            required>
                                        <option value="">Выберите категорию</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $announce->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Телефон --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Телефон <span class="text-danger">*</span></label>
                                    <input type="tel"
                                           name="phone"
                                           id="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $announce->phone ?? '') }}"
                                           placeholder="+996 XXX XXX XXX"
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $announce->email ?? '') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Цена --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="form-label">Цена</label>
                                    <input type="number"
                                           name="price"
                                           id="price"
                                           class="form-control @error('price') is-invalid @enderror"
                                           value="{{ old('price', $announce->price ?? '') }}">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Валюта --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency" class="form-label">Валюта</label>
                                    <select name="currency"
                                            id="currency"
                                            class="form-select @error('currency') is-invalid @enderror">
                                        <option value="KGS" {{ old('currency', $announce->currency ?? '') == 'KGS' ? 'selected' : '' }}>KGS</option>
                                        <option value="USD" {{ old('currency', $announce->currency ?? '') == 'USD' ? 'selected' : '' }}>USD</option>
                                    </select>
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Местоположение --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="locate" class="form-label">Местоположение</label>
                                    <input type="text"
                                           name="locate"
                                           id="locate"
                                           class="form-control @error('locate') is-invalid @enderror"
                                           value="{{ old('locate', $announce->locate ?? '') }}">
                                    @error('locate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Содержание --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content" class="form-label">Описание <span class="text-danger">*</span></label>
                                    <textarea name="content"
                                              id="content"
                                              class="form-control @error('content') is-invalid @enderror"
                                              rows="5"
                                              required>{{ old('content', $announce->content ?? '') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Изображение --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="image" class="form-label">Изображение</label>
                                    <input type="file"
                                           name="image"
                                           id="image"
                                           class="form-control @error('image') is-invalid @enderror"
                                           accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if(isset($announce) && $announce->image)
                                <div class="col-md-12">
                                    <div class="current-image">
                                        <img src="{{ asset($announce->image) }}" alt="Current Image" class="img-thumbnail" style="max-width: 200px">
                                        <div class="form-check mt-2">
                                            <input type="checkbox" name="remove_image" id="remove_image" class="form-check-input">
                                            <label for="remove_image" class="form-check-label">Удалить изображение</label>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Статус --}}
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox"
                                           name="check"
                                           id="check"
                                           class="form-check-input @error('check') is-invalid @enderror"
                                           value="1"
                                           {{ old('check', $announce->check ?? '') ? 'checked' : '' }}>
                                    <label for="check" class="form-check-label">Активно</label>
                                    @error('check')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.announces.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Назад
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                {{ isset($announce) ? 'Обновить' : 'Создать' }}
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
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#content').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endpush
