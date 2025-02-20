@extends("admin.layouts.app")

@section("title")
        {{ $$module_name_singular->name }} - {{ __($module_action) }} {{ __($module_title) }}
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
    <x-admin.layouts.show
        :data="$$module_name_singular"
        :module_name="$module_name"
        :module_path="$module_path"
        :module_title="$module_title"
        :module_icon="$module_icon"
        :module_action="$module_action"
    >
        <x-admin.section-header
            :data="$$module_name_singular"
            :module_name="$module_name"
            :module_title="$module_title"
            :module_icon="$module_icon"
            :module_action="$module_action"
        />

        <div class="row mt-4">
            <div class="col-12 col-sm-8">
                <x-admin.section-show-table :data="$$module_name_singular" :module_name="$module_name" />
            </div>
            <div class="col-12 col-sm-4">
                <h5>
                    Posts
                    <small>({{ count($posts) }})</small>
                </h5>
                <ul>
                    @foreach ($posts as $post)
                        <li>
                            <a href="{{ route("admin.posts.show", [$post->id, $post->slug]) }}">
                                {{ $post->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </x-admin.layouts.show>
@endsection
