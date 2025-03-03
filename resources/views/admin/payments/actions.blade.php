<div class="d-flex gap-2">
    <a href="{{ route('admin.payments.show', $data->id) }}"
       class="btn btn-primary btn-sm"
       title="Просмотр">
        <i class="fas fa-eye"></i>
    </a>
    @if($data->status === 'completed' && $data->refund_status === 'none')
    <button type="button"
            class="btn btn-warning btn-sm refund-payment"
            data-payment-id="{{ $data->id }}"
            title="Возврат">
        <i class="fas fa-undo"></i>
    </button>
    @endif
</div>
