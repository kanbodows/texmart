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
    <div class="card">
        <div class="card-body">
            <x-admin.section-header>
                <i class="{{ $module_icon }}"></i>
                {{ __($module_title) }}
                <small class="text-muted">{{ __($module_action) }}</small>

                <x-slot name="toolbar">
                    <x-admin.buttons.return-back :small="true" />
                </x-slot>
            </x-admin.section-header>

            <div class="row mb-3">
                <div class="col">
                    <strong>
                        @lang("Name")
                        :
                    </strong>
                    {{ $$module_name_singular->name }}
                </div>
                <div class="col">
                    <strong>
                        @lang("Email")
                        :
                    </strong>
                    {{ $$module_name_singular->email }}
                </div>
            </div>
            <div class="row mb-4 mt-4">
                <div class="col">
                    {{ html()->form("PATCH", route("admin.users.changePasswordUpdate", $$module_name_singular->id))->class("form-horizontal")->open() }}

                    <div class="form-group row mb-3">
                        {{ html()->label(__("labels.admin.users.fields.password"))->class("col-md-2 form-label")->for("password") }}

                        <div class="col-md-10">
                            {{ html()->password("password")->class("form-control")->placeholder(__("labels.admin.users.fields.password"))->required() }}
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        {{ html()->label(__("labels.admin.users.fields.password_confirmation"))->class("col-md-2 form-label")->for("password_confirmation") }}

                        <div class="col-md-10">
                            {{ html()->password("password_confirmation")->class("form-control")->placeholder(__("labels.admin.users.fields.password_confirmation"))->required() }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        {{ html()->button($text = "<i class='fas fa-save'></i> Save", $type = "submit")->class("btn btn-success") }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ html()->closeModelForm() }}
                </div>
                <!--/.col-->
            </div>
            <!--/.row-->
        </div>

        <div class="card-footer">
            <x-admin.section-footer>
                @lang("Updated")
                : {{ $$module_name_singular->updated_at->diffForHumans() }},
                @lang("Created at")
                : {{ $$module_name_singular->created_at->isoFormat("LLLL") }}
            </x-admin.section-footer>
        </div>
    </div>
@endsection
