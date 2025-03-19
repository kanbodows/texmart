@extends("admin.layouts.app")

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ isset($callback) ? 'Редактировать' : 'Создать' }} обратный звонок</h4>
                </div>

                <form action="{{ isset($callback) ? route('admin.callbacks.update', $callback->id) : route('admin.callbacks.store') }}"
                      method="POST">
                    @csrf
                    @if(isset($callback))
                        @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="row g-3">
                            {{-- Имя --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Имя <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $callback->name ?? '') }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Телефон --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Телефон <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="phone"
                                           id="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $callback->phone ?? '') }}"
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
                                           value="{{ old('email', $callback->email ?? '') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Статус --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">Статус <span class="text-danger">*</span></label>
                                    <select name="status"
                                            id="status"
                                            class="form-select @error('status') is-invalid @enderror"
                                            required>
                                        @foreach(\App\Enums\CallbackStatus::cases() as $status)
                                            <option value="{{ $status->value }}"
                                                {{ old('status', optional($callback)->status?->value) == $status->value ? 'selected' : '' }}>
                                                {{ $status->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- После блока статуса добавим информацию об обновлении --}}
                            @if(isset($callback) && $callback->updated_by)
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle fa-2x me-3"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <strong>Последнее обновление:</strong><br>
                                            {{ $callback->updater->name }}
                                            ({{ $callback->updated_at->format('d.m.Y H:i') }})
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Комментарий --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="comment" class="form-label">Комментарий</label>
                                    <textarea name="comment"
                                              id="comment"
                                              class="form-control @error('comment') is-invalid @enderror"
                                              rows="3"
                                              placeholder="Комментарий">{{ old('comment', $callback->comment ?? '') }}</textarea>
                                    @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.callbacks.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Назад
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                {{ isset($callback) ? 'Обновить' : 'Создать' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
