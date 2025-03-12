@extends("admin.layouts.app")

@section("title")
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section("content")
    <x-admin.layouts.show
        :data="$post"
        :module_name="$module_name"
        :module_path="$module_path"
        :module_title="$module_title"
        :module_icon="$module_icon"
        :module_action="$module_action"
    >
        <x-admin.section-header
            :data="$post"
            :module_name="$module_name"
            :module_title="$module_title"
            :module_icon="$module_icon"
            :module_action="$module_action"
        />

        <div class="row mt-4">
            <div class="col-12 col-sm-8">
                <x-admin.section-show-table :data="$post" :module_name="$module_name" />
            </div>
            <div class="col-12 col-sm-4">
                <h5>
                    Tags
                    <small>({{ count($post->tags) }})</small>
                </h5>

                <ul>
                    @foreach ($post->tags as $tag)
                        <li>
                            <a href="{{ route("admin.tags.show", [$tag->id, $tag->slug]) }}">
                                {{ $tag->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </x-admin.layouts.show>
@endsection
