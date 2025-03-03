<div class="text-end">
    <!-- <a
        href="{{ route("admin.announces.show", $data) }}"
        class="btn btn-success btn-sm mt-1"
        data-toggle="tooltip"
        title="{{ __("labels.admin.show") }}"
    >
        <i class="fas fa-desktop"></i>
    </a> -->

    <a href="#" class="btn btn-success btn-sm mt-1 responses-badge" data-announce-id="{{$data->id}}" title="Посмотреть отклики">
        {{$data->responses_count ?: 0}}
        <i class="fas fa-comments"></i>
    </a>
    <a
        href="{{ route("admin.announces.edit", $data) }}"
        class="btn btn-primary btn-sm mt-1"
        data-toggle="tooltip"
        title="{{ __("labels.admin.edit") }}"
    >
        <i class="fas fa-wrench"></i>
    </a>

    <form action="{{ route('admin.announces.destroy', $data->id) }}"
        method="POST"
        onsubmit="return confirm('Вы уверены?')"
        style="display: inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm mt-1" title="Удалить">
            <i class="fas fa-trash-alt"></i>
        </button>
    </form>
</div>
