<div class="row">
    <div class="col-12 col-sm-5 mb-3">
        <div class="form-group">
            {{ html()->label("Название", 'name')->class("form-label")->for('name') }}
            {!! field_required("required") !!}
            {{ html()->text('name')->placeholder("Название")->class("form-control")->attributes(["required"]) }}
        </div>
    </div>

    <div class="col-12 col-sm-3 mb-3">
        <div class="form-group">
            {{ html()->label("Slug", 'slug')->class("form-label")->for('slug') }}
            {!! field_required("") !!}
            {{ html()->text('slug')->placeholder("Slug")->class("form-control")->attributes([""]) }}
        </div>
    </div>

    <div class="col-12 col-sm-4 mb-3">
        <div class="form-group">
            {{ html()->label("Псевдоним автора", 'created_by_alias')->class("form-label")->for('created_by_alias') }}
            {!! field_required("") !!}
            {{ html()->text('created_by_alias')->placeholder("Скрыть имя автора и использовать псевдоним")->class("form-control")->attributes([""]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mb-3">
        <div class="form-group">
            {{ html()->label("Интро", 'intro')->class("form-label")->for('intro') }}
            {!! field_required("required") !!}
            {{ html()->textarea('intro')->placeholder("Интро")->class("form-control")->attributes(["required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mb-3">
        <div class="form-group">
            {{ html()->label("Содержание", 'content')->class("form-label")->for('content') }}
            {!! field_required("required") !!}
            {{ html()->textarea('content')->placeholder("Содержание")->class("form-control")->attributes(["required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mb-3">
        <div class="form-group">
            {{ html()->label("Изображение", 'image')->class("form-label")->for('image') }}
            {!! field_required("required") !!}
            <div class="input-group mb-3">
                {{ html()->text('image')->placeholder("Изображение")->class("form-control")->attributes(["required", "aria-label" => "Image", "aria-describedby" => "button-image"]) }}
                <button class="btn btn-outline-info" id="button-image" data-input="image" type="button">
                    <i class="fas fa-folder-open"></i>
                    &nbsp;
                    @lang("Обзор")
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- <div class="col-12 col-sm-4 mb-3">
        <div class="form-group">
            <?php
            $field_options = ! empty($data) ? optional($data->category())->pluck("name", "id") : "";
            $selected = ! empty($data)
                ? optional($data->category())
                    ->pluck("id")
                    ->toArray()
                : "";
            ?>
            {{ html()->label("Категория", 'category_id')->class("form-label")->for('category_id') }}
            {!! field_required("required") !!}
            {{ html()->select('category_id', $field_options, $selected)->placeholder("Выберите опцию")->class("form-select select2-category")->attributes(["required"]) }}
        </div>
    </div>
    <div class="col-12 col-sm-4 mb-3">
        <div class="form-group">
            <?php
            $select_options = \Modules\Post\Enums\PostType::toArray();
            ?>
            {{ html()->label("Тип", 'type')->class("form-label")->for('type') }}
            {!! field_required("required") !!}
            {{ html()->select('type', $select_options)->class("form-select")->attributes(["required"]) }}
        </div>
    </div>-->
    <div class="col-12 col-sm-4 mb-3">
        <div class="form-group">
            <?php
            $select_options = [
                "0" => "Нет",
                "1" => "Да",
            ];
            ?>
            {{ html()->label("Избранное", 'is_featured')->class("form-label")->for('is_featured') }}
            {!! field_required("required") !!}
            {{ html()->select('is_featured', $select_options)->class("form-select")->attributes(["required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mb-3">
        <div class="form-group">
            <?php
            $field_options = ! empty($data) ? optional($data->tags)->pluck("name", "id") : "";
            $selected = ! empty($data)
                ? optional($data->tags)
                    ->pluck("id")
                    ->toArray()
                : "";
            ?>
            {{ html()->label("Теги", 'tags_list[]')->class("form-label")->for('tags_list[]') }}
            {!! field_required("") !!}
            {{ html()->multiselect('tags_list[]', $field_options, $selected)->class("form-control select2-tags")->attributes([""]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6 mb-3">
        <div class="form-group">
            <?php
            $select_options = \Modules\Post\Enums\PostStatus::toArray();
            ?>
            {{ html()->label("Статус", 'status')->class("form-label")->for('status') }}
            {!! field_required("required") !!}
            {{ html()->select('status', $select_options)->placeholder("Выберите опцию")->class("form-select")->attributes(["required"]) }}
        </div>
    </div>
    <div class="col-12 col-sm-6 mb-3">
        <div class="form-group">
            {{ html()->label("Дата публикации", 'published_at')->class("form-label")->for('published_at') }}
            {!! field_required("required") !!}
            {{ html()->datetime('published_at')->placeholder("Дата публикации")->class("form-control")->attributes(["required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-5 mb-3">
        <div class="form-group">
            {{ html()->label("Мета заголовок", 'meta_title')->class("form-label")->for('meta_title') }}
            {!! field_required("") !!}
            {{ html()->text('meta_title')->placeholder("Мета заголовок")->class("form-control")->attributes([""]) }}
        </div>
    </div>
    <div class="col-12 col-sm-5 mb-3">
        <div class="form-group">
            {{ html()->label("Мета ключевые слова", 'meta_keywords')->class("form-label")->for('meta_keywords') }}
            {!! field_required("") !!}
            {{ html()->text('meta_keywords')->placeholder("Мета ключевые слова")->class("form-control")->attributes([""]) }}
        </div>
    </div>
    <div class="col-12 col-sm-2 mb-3">
        <div class="form-group">
            {{ html()->label("Порядок", 'order')->class("form-label")->for('order') }}
            {!! field_required("") !!}
            {{ html()->text('order')->placeholder("Порядок")->class("form-control")->attributes([""]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6 mb-3">
        <div class="form-group">
            {{ html()->label("Мета описание", 'meta_description')->class("form-label")->for('meta_description') }}
            {!! field_required("") !!}
            {{ html()->text('meta_description')->placeholder("Мета описание")->class("form-control")->attributes([""]) }}
        </div>
    </div>
    <div class="col-12 col-sm-6 mb-3">
        <div class="form-group">
            {{ html()->label("Мета OG изображение", 'meta_og_image')->class("form-label")->for('meta_og_image') }}
            {!! field_required("") !!}
            {{ html()->text('meta_og_image')->placeholder("Мета OG изображение")->class("form-control")->attributes([""]) }}
        </div>
    </div>
</div>

@push("after-styles")
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet" />
    <style>
        .note-editor.note-frame :after {
            display: none;
        }

        .note-editor .note-toolbar .note-dropdown-menu,
        .note-popover .popover-content .note-dropdown-menu {
            min-width: 180px;
        }
    </style>
@endpush

@push("after-scripts")
    <script
        type="module"
        src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"
    ></script>
    <script type="module">
        // Define function to open filemanager window
        var lfm = function (options, cb) {
            var route_prefix = options && options.prefix ? options.prefix : '/laravel-filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        // Define LFM summernote button
        var LFMButton = function (context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="note-icon-picture"></i> ',
                tooltip: 'Insert image with filemanager',
                click: function () {
                    lfm(
                        {
                            type: 'image',
                            prefix: '/laravel-filemanager',
                        },
                        function (lfmItems, path) {
                            lfmItems.forEach(function (lfmItem) {
                                context.invoke('insertImage', lfmItem.url);
                            });
                        },
                    );
                },
            });
            return button.render();
        };

        $('#content').summernote({
            height: 120,
            toolbar: [
                ['style', ['style']],
                ['font', ['fontname', 'fontsize', 'bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'lfm', 'video']],
                ['view', ['codeview', 'undo', 'redo', 'help']],
            ],
            buttons: {
                lfm: LFMButton,
            },
        });
    </script>

    <script type="module" src="{{ asset("vendor/laravel-filemanager/js/stand-alone-button.js") }}"></script>
    <script type="module">
        $('#button-image').filemanager('image');
    </script>

    <!-- Select2 Library -->
    <x-library.select2 />
    <script type="module">
        $(document).ready(function () {
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
                document.querySelector('.select2-container--open .select2-search__field').focus();
            });

            $('.select2-category').select2({
                theme: 'bootstrap-5',
                placeholder: '@lang("Select an option")',
                minimumInputLength: 2,
                allowClear: true,
                ajax: {
                    url: '{{ route("admin.categories.index_list") }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term),
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data,
                        };
                    },
                    cache: true,
                },
            });

            $('.select2-tags').select2({
                theme: 'bootstrap-5',
                placeholder: '@lang("Select an option")',
                minimumInputLength: 2,
                allowClear: true,
                ajax: {
                    url: '{{ route("admin.tags.index_list") }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term),
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data,
                        };
                    },
                    cache: true,
                },
            });
        });
    </script>
@endpush
