@extends("admin.layouts.app")

@section("breadcrumbs")
    <x-admin.breadcrumbs>
        <x-admin.breadcrumb-item type="active" icon="{{ $module_icon }}">
            {{ __($module_title) }}
        </x-admin.breadcrumb-item>
    </x-admin.breadcrumbs>
@endsection

@section("content")
    <div class="card">
        <div class="card-body">
            <x-admin.section-header
                :module_name="$module_name"
                :module_title="$module_title"
                :module_icon="$module_icon"
                :module_action="$module_action"
            />

            <div class="row mt-4">
                <div class="col">
                    {{ html()->form("POST", route("admin.$module_name.store"))->open() }}

                    @if (count(config("setting_fields", [])))
                        @foreach (config("setting_fields") as $section => $fields)
                            <div class="card card-accent-primary mb-4">
                                <div class="card-header">
                                    <i class="{{ Arr::get($fields, "icon", "glyphicon glyphicon-flash") }}"></i>
                                    &nbsp;{{ $fields["title"] }}
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">{{ $fields["desc"] }}</p>

                                    <div class="row mt-3">
                                        <div class="col">
                                            @foreach ($fields["elements"] as $field)
                                                @includeIf("admin.settings.fields." . $field["type"])
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <div class="row m-b-md">
                        <div class="col-md-12">
                            <x-admin.buttons.save />
                        </div>
                    </div>

                    {{ html()->form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
