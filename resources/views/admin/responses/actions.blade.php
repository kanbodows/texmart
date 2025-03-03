<div class="text-end">
    <a href="{{ route('admin.responses.show', $data) }}"
       class="btn btn-info btn-sm"
       data-bs-toggle="tooltip"
       title="Просмотр">
        <i class="fas fa-eye"></i>
    </a>

    <button type="button"
            class="btn btn-primary btn-sm show-details"
            data-bs-toggle="tooltip"
            title="Быстрый просмотр"
            data-url="{{ route('admin.responses.show', $data) }}"
            data-modal-id="responseDetailsModal">
        <i class="fas fa-search"></i>
    </button>
</div>
