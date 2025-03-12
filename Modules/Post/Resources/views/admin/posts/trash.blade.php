@extends("admin.layouts.app")

@section("title")
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section("content")
    <x-admin.layouts.trash
        :data="$$module_name"
        :module_name="$module_name"
        :module_path="$module_path"
        :module_title="$module_title"
        :module_icon="$module_icon"
    />
@endsection
