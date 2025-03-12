@extends("admin.layouts.app")

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ isset($user) ? 'Редактировать' : 'Создать' }} пользователя</h4>
                </div>

                <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @if(isset($user))
                        @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="row g-3">
                            {{-- Аватар --}}
                            <div class="col-md-12">
                                <div class="form-group row">
                                    {{ html()->label(__("labels.admin.users.fields.avatar"))->class("col-md-2 form-label")->for("avatar") }}

                                    <div class="col-md-5 mb-3">
                                        @if(isset($user) && $user->avatar)
                                        <img
                                            class="user-profile-image img-fluid img-thumbnail"
                                            src="{{ asset($user->avatar) }}"
                                            style="max-height: 200px; max-width: 200px"
                                        />
                                        @endif
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <input id="file-multiple-input" name="avatar" type="file" multiple="" />
                                    </div>
                                </div>
                            </div>

                            {{-- Имя --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Имя <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name ?? '') }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email ?? '') }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Мобильный --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobile" class="form-label">Мобильный</label>
                                    <input type="text"
                                           name="mobile"
                                           id="mobile"
                                           class="form-control @error('mobile') is-invalid @enderror"
                                           value="{{ old('mobile', $user->mobile ?? '') }}">
                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Пол --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender" class="form-label">Пол</label>
                                    <select name="gender"
                                            id="gender"
                                            class="form-select @error('gender') is-invalid @enderror">
                                        <option value="">-- Выберите опцию --</option>
                                        <option value="Female" {{ old('gender', $user->gender ?? '') == 'Female' ? 'selected' : '' }}>Женский</option>
                                        <option value="Male" {{ old('gender', $user->gender ?? '') == 'Male' ? 'selected' : '' }}>Мужской</option>
                                        <option value="Other" {{ old('gender', $user->gender ?? '') == 'Other' ? 'selected' : '' }}>Другой</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Дата рождения --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth" class="form-label">Дата рождения</label>
                                    <input type="date"
                                           name="date_of_birth"
                                           id="date_of_birth"
                                           class="form-control @error('date_of_birth') is-invalid @enderror"
                                           value="{{ old('date_of_birth', $user->date_of_birth ?? '') }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Адрес --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address" class="form-label">Адрес</label>
                                    <textarea name="address"
                                              id="address"
                                              class="form-control @error('address') is-invalid @enderror"
                                              placeholder="Адрес">{{ old('address', $user->address ?? '') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Пароль (только для создания) --}}
                            @if(!isset($user))
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">Пароль <span class="text-danger">*</span></label>
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Подтверждение пароля (только для создания) --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">Подтверждение пароля <span class="text-danger">*</span></label>
                                    <input type="password"
                                           name="password_confirmation"
                                           id="password_confirmation"
                                           class="form-control @error('password_confirmation') is-invalid @enderror"
                                           required>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            @endif

                            {{-- Статус --}}
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox"
                                           name="status"
                                           id="status"
                                           class="form-check-input @error('status') is-invalid @enderror"
                                           value="1"
                                           {{ old('status', $user->status ?? true) ? 'checked' : '' }}>
                                    <label for="status" class="form-check-label">Активный</label>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Подтвержден --}}
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox"
                                           name="confirmed"
                                           id="confirmed"
                                           class="form-check-input @error('confirmed') is-invalid @enderror"
                                           value="1"
                                           {{ old('confirmed', $user->confirmed ?? true) ? 'checked' : '' }}>
                                    <label for="confirmed" class="form-check-label">Email подтвержден</label>
                                    @error('confirmed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Отправить учетные данные по email --}}
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox"
                                           name="email_credentials"
                                           id="email_credentials"
                                           class="form-check-input @error('email_credentials') is-invalid @enderror"
                                           value="1"
                                           {{ old('email_credentials', $user->email_credentials ?? false) ? 'checked' : '' }}>
                                    <label for="email_credentials" class="form-check-label">Отправить учетные данные по email</label>
                                    @error('email_credentials')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Роли --}}
                            <div class="col-md-6">
                                <div class="card card-accent-info">
                                    <div class="card-header">
                                        @lang("Роли")
                                    </div>
                                    <div class="card-body">
                                        @if ($roles->count())
                                            @foreach ($roles as $role)
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <div class="checkbox">
                                                            <div class="form-check">
                                                                <input
                                                                    class="form-check-input"
                                                                    id="{{ "role-" . $role->id }}"
                                                                    name="roles[]"
                                                                    type="checkbox"
                                                                    value="{{ $role->id }}"
                                                                    {{ (isset($user) && in_array($role->name, $user->roles->pluck('name')->toArray())) || (is_array(old('roles')) && in_array($role->name, old('roles'))) ? "checked" : "" }}
                                                                />
                                                                <label
                                                                    class="form-check-label"
                                                                    for="{{ "role-" . $role->id }}"
                                                                >
                                                                    {{ label_case($role->name) . " (" . $role->name . ")" }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        @if ($role->id != 1)
                                                            @if ($role->permissions->count())
                                                                @foreach ($role->permissions as $permission)
                                                                    <i class="far fa-check-circle fa-fw mr-1"></i>
                                                                    &nbsp;{{ $permission->name }}&nbsp;
                                                                @endforeach
                                                            @else
                                                                @lang("Нет")
                                                            @endif
                                                        @else
                                                            @lang("Все разрешения")
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Разрешения --}}
                            <div class="col-md-6">
                                <div class="card card-accent-primary">
                                    <div class="card-header">
                                        @lang("Разрешения")
                                    </div>
                                    <div class="card-body">
                                        @if ($permissions->count())
                                            @foreach ($permissions as $permission)
                                                <div class="mb-2">
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input"
                                                            id="{{ "permission-" . $permission->id }}"
                                                            name="permissions[]"
                                                            type="checkbox"
                                                            value="{{ $permission->name }}"
                                                            {{ (isset($user) && in_array($permission->name, $user->permissions->pluck('name')->toArray())) || (is_array(old('permissions')) && in_array($permission->name, old('permissions'))) ? "checked" : "" }}
                                                        />
                                                        <label
                                                            class="form-check-label"
                                                            for="{{ "permission-" . $permission->id }}"
                                                        >
                                                            {{ label_case($permission->name) . " (" . $permission->name . ")" }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Назад
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                {{ isset($user) ? 'Обновить' : 'Создать' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
