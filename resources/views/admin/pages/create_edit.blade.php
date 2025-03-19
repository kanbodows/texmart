@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ isset($page) ? 'Редактировать' : 'Создать' }} страницу</h4>
                </div>

                <form action="{{ isset($page) ? route('admin.pages.update', $page->id) : route('admin.pages.store') }}"
                      method="POST">
                    @csrf
                    @if(isset($page))
                        @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="row g-3">
                            {{-- Заголовок --}}
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title" class="form-label">Заголовок <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="title"
                                           id="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title', $page->title ?? '') }}"
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Slug --}}
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="slug" class="form-label">URL (slug)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">/</span>
                                        <input type="text"
                                               name="slug"
                                               id="slug"
                                               class="form-control @error('slug') is-invalid @enderror"
                                               value="{{ old('slug', $page->slug ?? '') }}">
                                        @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Оставьте пустым для автоматической генерации из заголовка</small>
                                </div>
                            </div>

                            {{-- Порядок --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="order" class="form-label">Порядок</label>
                                    <input type="number"
                                           name="order"
                                           id="order"
                                           class="form-control @error('order') is-invalid @enderror"
                                           value="{{ old('order', $page->order ?? 0) }}">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Статус --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label d-block">Статус</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox"
                                               name="status"
                                               id="status"
                                               class="form-check-input @error('status') is-invalid @enderror"
                                               value="1"
                                               {{ old('status', $page->status ?? true) ? 'checked' : '' }}>
                                        <label for="status" class="form-check-label">Активна</label>
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Контент --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="content" class="form-label">Контент <span class="text-danger">*</span></label>
                                    <textarea name="content"
                                              id="content"
                                              class="form-control @error('content') is-invalid @enderror"
                                              rows="20"
                                              required>{{ old('content', $page->content ?? '') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Meta Title --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text"
                                           name="meta_title"
                                           id="meta_title"
                                           class="form-control @error('meta_title') is-invalid @enderror"
                                           value="{{ old('meta_title', $page->meta_title ?? '') }}">
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Meta Description --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea name="meta_description"
                                              id="meta_description"
                                              class="form-control @error('meta_description') is-invalid @enderror"
                                              rows="3">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if(isset($page) && $page->updated_by)
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle fa-2x me-3"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <strong>Последнее обновление:</strong><br>
                                            {{ $page->updater->name }}
                                            ({{ $page->updated_at->format('d.m.Y H:i') }})
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Назад
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                {{ isset($page) ? 'Обновить' : 'Создать' }}
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
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('after-scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#content').summernote({
            height: 500,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
        });
    });
</script>
@endpush
