<div class="text-end">
    <a href="{{ route('admin.feedbacks.show', $data) }}"
       class="btn btn-info btn-sm"
       data-bs-toggle="tooltip"
       title="Просмотр">
        <i class="fas fa-eye"></i>
    </a>

    <a href="{{ route('admin.feedbacks.destroy', $data) }}"
       class="btn btn-danger btn-sm"
       data-method="DELETE"
       data-token="{{ csrf_token() }}"
       data-toggle="tooltip"
       title="Удалить"
       data-confirm="Вы уверены?">
        <i class="fas fa-trash"></i>
    </a>
</div>
