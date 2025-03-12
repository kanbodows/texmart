@extends("admin.layouts.app")

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
