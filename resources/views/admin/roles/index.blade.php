@extends("admin.layouts.app")

@section("content")
    <div class="card">
        <div class="card-body">
            <x-admin.section-header>
                <i class="{{ $module_icon }}"></i>
                {{ __($module_title) }}
                <small class="text-muted">{{ __($module_action) }}</small>

                <x-slot name="toolbar">
                    <x-admin.buttons.create
                        title="{{ __('Create') }} {{ ucwords(Str::singular($module_name)) }}"
                        route='{{ route("admin.$module_name.create") }}'
                        :small="true"
                    />
                </x-slot>
            </x-admin.section-header>

            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table-hover table-bordered table">
                            <thead>
                                <tr>
                                    <th>{{ __("labels.admin.$module_name.fields.name") }}</th>
                                    <th>{{ __("labels.admin.$module_name.fields.permissions") }}</th>
                                    <th class="text-end">{{ __("labels.admin.action") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($$module_name as $module_name_singular)
                                    <tr>
                                        <td>
                                            <strong>
                                                {{ $module_name_singular->name }}
                                            </strong>
                                        </td>
                                        <td>
                                            <ul>
                                                @foreach ($module_name_singular->permissions as $permission)
                                                    <li>{{ $permission->name }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-end">
                                            @can("edit_" . $module_name)
                                                <x-admin.buttons.edit
                                                    title="{{ __('Edit') }} {{ ucwords(Str::singular($module_name)) }}"
                                                    route='{!! route("admin.$module_name.edit", $module_name_singular) !!}'
                                                    small="true"
                                                />
                                            @endcan

                                            <x-admin.buttons.show
                                                title="{{ __('Show') }} {{ ucwords(Str::singular($module_name)) }}"
                                                route='{!! route("admin.$module_name.show", $module_name_singular) !!}'
                                                small="true"
                                            />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12 col-sm-7">
                    <div class="float-left">{!! $$module_name->total() !!} {{ __("labels.admin.total") }}</div>
                </div>
                <div class="col-12 col-sm-5">
                    <div class="float-end">
                        {{ $$module_name->links("pagination::bootstrap-5") }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
