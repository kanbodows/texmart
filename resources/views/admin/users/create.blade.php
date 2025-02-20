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

@section("content")
    <x-admin.layouts.create>
        <x-admin.section-header>
            <i class="{{ $module_icon }}"></i>
            {{ __($module_title) }}
            <small class="text-muted">{{ __($module_action) }}</small>

            <x-slot name="toolbar">
                <x-admin.buttons.return-back :small="true" />
            </x-slot>
        </x-admin.section-header>

        <div class="row mt-4">
            <div class="col">
                {{ html()->form("POST", route("admin.$module_name.store"))->acceptsFiles()->open() }}

                <div class="row">
                    <div class="col-12 col-sm-6 mb-3">
                        <div class="form-group">
                            <?php
                            $field_name = "first_name";
                            $field_lable = label_case($field_name);
                            $field_placeholder = $field_lable;
                            $required = "required";
                            ?>

                            {{ html()->label($field_lable, $field_name)->class("form-label") }}
                            {!! field_required($required) !!}
                            {{ html()->text($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 mb-3">
                        <div class="form-group">
                            <?php
                            $field_name = "last_name";
                            $field_lable = label_case($field_name);
                            $field_placeholder = $field_lable;
                            $required = "required";
                            ?>

                            {{ html()->label($field_lable, $field_name)->class("form-label") }}
                            {!! field_required($required) !!}
                            {{ html()->text($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 mb-3">
                        <div class="form-group">
                            <?php
                            $field_name = "email";
                            $field_lable = label_case($field_name);
                            $field_placeholder = $field_lable;
                            $required = "required";
                            ?>

                            {{ html()->label($field_lable, $field_name)->class("form-label") }}
                            {!! field_required($required) !!}
                            {{ html()->email($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 mb-3">
                        <div class="form-group">
                            <?php
                            $field_name = "mobile";
                            $field_lable = label_case($field_name);
                            $field_placeholder = $field_lable;
                            $required = "";
                            ?>

                            {{ html()->label($field_lable, $field_name)->class("form-label") }}
                            {!! field_required($required) !!}
                            {{ html()->text($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 mb-3">
                        <div class="form-group">
                            <?php
                            $field_name = "password";
                            $field_lable = label_case($field_name);
                            $field_placeholder = $field_lable;
                            $required = "required";
                            ?>

                            {{ html()->label($field_lable, $field_name)->class("form-label") }}
                            {!! field_required($required) !!}
                            {{ html()->password($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 mb-3">
                        <div class="form-group">
                            <?php
                            $field_name = "password_confirmation";
                            $field_lable = label_case($field_name);
                            $field_placeholder = $field_lable;
                            $required = "required";
                            ?>

                            {{ html()->label($field_lable, $field_name)->class("form-label") }}
                            {!! field_required($required) !!}
                            {{ html()->password($field_name)->placeholder($field_placeholder)->class("form-control")->attributes(["$required"]) }}
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    {{ html()->label(__("labels.admin.users.fields.status"))->class("col-6 col-sm-2 form-label")->for("status") }}

                    <div class="col-6 col-sm-10">
                        {{ html()->checkbox("status", true, "1") }}
                        @lang("Active")
                    </div>
                </div>

                <div class="form-group row mb-3">
                    {{ html()->label(__("labels.admin.users.fields.confirmed"))->class("col-6 col-sm-2 form-label")->for("confirmed") }}

                    <div class="col-6 col-sm-10">
                        {{ html()->checkbox("confirmed", true, "1") }}
                        @lang("Email Confirmed")
                    </div>
                </div>

                <div class="form-group row mb-3">
                    {{ html()->label(__("labels.admin.users.fields.email_credentials"))->class("col-6 col-sm-2 form-label")->for("confirmed") }}

                    <div class="col-6 col-sm-10">
                        {{ html()->checkbox("email_credentials", true, "1") }}
                        @lang("Email Credentials")
                    </div>
                </div>

                <div class="form-group row mb-3">
                    {{ html()->label("Abilities")->class("col-sm-2 form-label") }}

                    <div class="col">
                        <div class="row">
                            <div class="col-12 col-sm-7 mb-3">
                                <div class="card card-accent-info">
                                    <div class="card-header">
                                        @lang("Roles")
                                    </div>
                                    <div class="card-body">
                                        @if ($roles->count())
                                            @foreach ($roles as $role)
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <div class="checkbox">
                                                            {{ html()->label( html()->checkbox("roles[]", old("roles") && in_array($role->name, old("roles")) ? true : false, $role->name)->id("role-" . $role->id) ."&nbsp;" . ucwords($role->name) ."&nbsp;(" .$role->name .")",)->for("role-" . $role->id) }}
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        @if ($role->id != 1)
                                                            @if ($role->permissions->count())
                                                                @foreach ($role->permissions as $permission)
                                                                    <i class="far fa-check-circle mr-1"></i>
                                                                    &nbsp;{{ $permission->name }}&nbsp;
                                                                @endforeach
                                                            @else
                                                                @lang("None")
                                                            @endif
                                                        @else
                                                            @lang("All Permissions")
                                                        @endif
                                                    </div>
                                                </div>
                                                <!--card-->
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-5 mb-3">
                                <div class="card card-accent-primary">
                                    <div class="card-header">
                                        @lang("Permissions")
                                    </div>
                                    <div class="card-body">
                                        @if ($permissions->count())
                                            @foreach ($permissions as $permission)
                                                <div class="checkbox">
                                                    {{ html()->label( html()->checkbox("permissions[]", old("permissions") && in_array($permission->name, old("permissions")) ? true : false, $permission->name)->id("permission-" . $permission->id) ." " .$permission->name,)->for("permission-" . $permission->id) }}
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <x-admin.buttons.create>Create</x-admin.buttons.create>
                    </div>
                    <div class="col-6">
                        <div class="float-end">
                            <x-admin.buttons.cancel />
                        </div>
                    </div>
                </div>

                {{ html()->form()->close() }}
            </div>
        </div>
    </x-admin.layouts.create>
@endsection
