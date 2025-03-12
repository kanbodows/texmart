@extends("admin.layouts.app")

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
                    {{ $user->name }}
                </div>
                <div class="col">
                    <strong>
                        @lang("Email")
                        :
                    </strong>
                    {{ $user->email }}
                </div>
            </div>
            <div class="row mb-4 mt-4">
                <div class="col">
                    {{ html()->form("PATCH", route("admin.users.changePasswordUpdate", $user->id))->class("form-horizontal")->open() }}

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
                : {{ $user->updated_at->diffForHumans() }},
                @lang("Created at")
                : {{ $user->created_at->isoFormat("LLLL") }}
            </x-admin.section-footer>
        </div>
    </div>
@endsection
