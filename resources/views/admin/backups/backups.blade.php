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
                    @can("add_" . $module_name)
                        <x-admin.buttons.create
                            route='{{ route("admin.$module_name.create") }}'
                            title="{{ __(ucwords(Str::singular($module_name))) }} {{ __('Create') }}"
                        />
                    @endcan
                </x-slot>
            </x-admin.section-header>

            <div class="row mt-4">
                <div class="col">
                    @if (count($backups))
                        <div class="table-responsive">
                            <table id="datatable" class="table-bordered table-hover table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>
                                            @lang("File")
                                        </th>
                                        <th>
                                            @lang("Size")
                                        </th>
                                        <th>
                                            @lang("Date")
                                        </th>
                                        <th>
                                            @lang("Age")
                                        </th>
                                        <th class="text-end">
                                            @lang("Action")
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($backups as $key => $backup)
                                        <tr>
                                            <td>
                                                {{ ++$key }}
                                            </td>
                                            <td>
                                                {{ $backup["file_name"] }}
                                            </td>
                                            <td>
                                                {{ $backup["file_size"] }}
                                            </td>
                                            <td>
                                                {{ $backup["date_created"] }}
                                            </td>
                                            <td>
                                                {{ $backup["date_ago"] }}
                                            </td>
                                            <td class="text-end">
                                                <a
                                                    href="{{ route("admin.$module_name.download", $backup["file_name"]) }}"
                                                    class="btn btn-primary btn-sm m-1"
                                                    data-toggle="tooltip"
                                                    title="@lang("Download File")"
                                                >
                                                    <i class="fas fa-cloud-download-alt"></i>
                                                    &nbsp;
                                                    @lang("Download")
                                                </a>

                                                <a
                                                    href="{{ route("admin.$module_name.delete", $backup["file_name"]) }}"
                                                    class="btn btn-danger btn-sm m-1"
                                                    data-toggle="tooltip"
                                                    title="@lang("Delete File")"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                    &nbsp;
                                                    @lang("Delete")
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <p>@lang("No backup has been created yet!")</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
