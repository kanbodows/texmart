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
    @if($module_action == 'Редактировать')
        <x-admin.layouts.edit
            :data="$post"
            :module_name="$module_name"
            :module_path="$module_path"
            :module_title="$module_title"
            :module_icon="$module_icon"
            :module_action="$module_action"
            module_action_label='Редактировать'
        />
    @else
        <x-admin.layouts.create
            :module_name="$module_name"
            :module_path="$module_path"
            :module_title="$module_title"
            :module_icon="$module_icon"
            :module_action="$module_action"
            module_action_label='Создать'
        />
    @endif
@endsection
