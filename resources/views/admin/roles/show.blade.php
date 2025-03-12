@extends("admin.layouts.app")

@section("content")
    <x-admin.layouts.show :data="$$module_name_singular">
        <x-admin.section-header>
            <i class="{{ $module_icon }} fa-fw"></i>
            {{ $$module_name_singular->name }}
            <small class="text-muted">{{ __($module_title) }}</small>

            <x-slot name="toolbar">
                <x-admin.buttons.return-back :small="true" />
                <x-admin.buttons.edit
                    class="ms-1"
                    title="{{ __('Edit') }} {{ ucwords(Str::singular($module_name)) }}"
                    route='{!! route("admin.$module_name.edit", $$module_name_singular) !!}'
                    :small="true"
                />
            </x-slot>
        </x-admin.section-header>

        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="table-responsive">
                    <table class="table-bordered table-hover table">
                        <tr>
                            <th>{{ __("labels.admin.$module_name.fields.name") }}</th>
                            <td>{{ $$module_name_singular->name }}</td>
                        </tr>

                        <tr>
                            <th>{{ __("labels.admin.$module_name.fields.permissions") }}</th>
                            <td>
                                @if ($$module_name_singular->permissions()->count() > 0)
                                    <ul>
                                        @foreach ($$module_name_singular->permissions as $permission)
                                            <li>{{ $permission->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>{{ __("labels.admin.$module_name.fields.created_at") }}</th>
                            <td>
                                {{ $$module_name_singular->created_at }}
                                <br />
                                <small>({{ $$module_name_singular->created_at->diffForHumans() }})</small>
                            </td>
                        </tr>

                        <tr>
                            <th>{{ __("labels.admin.$module_name.fields.updated_at") }}</th>
                            <td>
                                {{ $$module_name_singular->updated_at }}
                                <br />
                                <small>({{ $$module_name_singular->updated_at->diffForHumans() }})</small>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="table-responsive">
                    <table class="table-bordered table">
                        <thead>
                            <tr>
                                <th>
                                    List of users (
                                    <small>Total: {{ $users->count() }}</small>
                                    )
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <a href="{{ route("admin.users.show", $user->id) }}">{{ $user->name }}</a>
                                        <span class="float-end">{!! $user->status_label !!}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-admin.layouts.show>
@endsection
