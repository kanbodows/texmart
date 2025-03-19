@extends("admin.layouts.app")

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ isset($post) ? 'Редактировать' : 'Создать' }} баннер</h4>
                </div>

                <form action="{{ isset($post) ? route('admin.ads.update', $post->id) : route('admin.ads.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @if(isset($post))
                        @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="row g-3">
                            {{-- Название --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title" class="form-label">Название <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="title"
                                           id="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title', $post->title ?? '') }}"
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Содержание --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content" class="form-label">Содержание <span class="text-danger">*</span></label>
                                    <textarea name="content"
                                              id="content"
                                              class="form-control @error('content') is-invalid @enderror"
                                              rows="5"
                                              required>{{ old('content', $post->content ?? '') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Порядок --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order" class="form-label">Порядок сортировки</label>
                                    <input type="number"
                                           name="order"
                                           id="order"
                                           class="form-control @error('order') is-invalid @enderror"
                                           value="{{ old('order', $post->order ?? 0) }}">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Изображение --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="image">Изображение</label>
                                    <input type="file"
                                           class="form-control-file"
                                           id="image"
                                           name="image"
                                           accept="image/*">
                                </div>
                            </div>

                            @if(isset($post) && $post->image)
                            <div class="col-md-12">
                                <img src="{{ asset('storage/' . $post->image) }}"
                                     alt="Изображение"
                                     class="img-thumbnail"
                                     style="max-width: 200px">
                            </div>
                            @endif

                            {{-- Видео --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="video" class="form-label">Видео</label>
                                    <textarea name="video"
                                              id="video"
                                              class="form-control @error('video') is-invalid @enderror"
                                              rows="3"
                                              placeholder="Вставьте код для встраивания видео">{{ old('video', $post->video ?? '') }}</textarea>
                                    @error('video')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Вставьте код iframe для встраивания видео</small>
                                </div>
                            </div>

                            {{-- Статус --}}
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox"
                                           name="status"
                                           id="status"
                                           class="form-check-input @error('status') is-invalid @enderror"
                                           value="1"
                                           {{ old('status', $post->status ?? '') ? 'checked' : '' }}>
                                    <label for="status" class="form-check-label">Активно</label>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Назад
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                {{ isset($post) ? 'Обновить' : 'Создать' }}
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
