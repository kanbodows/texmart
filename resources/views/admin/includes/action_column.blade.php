<div class="text-end">
    @can("edit_" . $module_name)
        <x-admin.buttons.edit
            route='{!! route("admin.$module_name.edit", $data) !!}'
            title="{{ __('Edit') }} {{ ucwords(Str::singular($module_name)) }}"
            small="true"
        />
    @endcan

    <x-admin.buttons.show
        route='{!! route("admin.$module_name.show", $data) !!}'
        title="{{ __('Show') }} {{ ucwords(Str::singular($module_name)) }}"
        small="true"
    />
</div>
