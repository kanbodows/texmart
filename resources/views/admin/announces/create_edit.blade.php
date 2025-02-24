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
                                    <label for="images">Фотографии (максимум 5)</label>
                                    <input type="file"
                                           class="form-control-file"
                                           id="images"
                                           name="images[]"
                                           multiple
                                           accept="image/*"
                                           data-max-files="5">
                                    <small class="text-muted">Можно загрузить до 5 фотографий</small>
                                </div>
                            </div>

                            @if(isset($announce) && $announce->images)
                            <div class="row mt-3" id="images-preview">
                                @foreach($announce->images as $index => $image)
                                <div class="col-md-3 mb-3 image-container">
                                    <div class="card">
                                        <img src="{{ asset('storage/' . $image) }}" class="card-img-top" alt="Фото объявления">
                                        <div class="card-body p-2">
                                            <button type="button"
                                                    class="btn btn-danger btn-sm delete-image"
                                                    data-index="{{ $index }}">
                                                Удалить
                                            </button>
                                            <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('images');
        const previewContainer = document.getElementById('images-preview') ||
            document.createElement('div');
        const maxFiles = 5;

        if (!document.getElementById('images-preview')) {
            previewContainer.id = 'images-preview';
            previewContainer.className = 'row mt-3';
            imageInput.parentNode.after(previewContainer);
        }

        imageInput.addEventListener('change', function() {
            const existingImages = document.querySelectorAll('.image-container').length;
            const selectedFiles = Array.from(this.files);

            if (existingImages + selectedFiles.length > maxFiles) {
                alert(`Можно загрузить максимум ${maxFiles} фотографий. У вас уже загружено ${existingImages} фото.`);
                this.value = ''; // очищаем выбранные файлы
                return;
            }

            selectedFiles.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'col-md-3 mb-3 image-container';
                    div.innerHTML = `
                        <div class="card">
                            <img src="${e.target.result}" class="card-img-top" alt="Предпросмотр">
                            <div class="card-body p-2">
                                <button type="button" class="btn btn-danger btn-sm delete-image">
                                    Удалить
                                </button>
                            </div>
                        </div>
                    `;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });

        // Удаление фотографий
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-image')) {
                const container = e.target.closest('.image-container');
                container.remove();
                // Очищаем input file, чтобы можно было загрузить новые фото
                imageInput.value = '';
            }
        });
    });
    </script>
@endpush
