<div class="text-end">
    @can('user_show')
        <a
            href="{{ route("admin.users.show", $data) }}"
            class="btn btn-info btn-sm"
            data-bs-toggle="tooltip"
            title="Просмотр"
        >
            <i class="fas fa-eye"></i>
        </a>
    @endcan

    <a
        href="{{ route("admin.payments.index", ["user_id" => $data->id]) }}"
        class="btn btn-primary btn-sm"
        data-bs-toggle="tooltip"
        title="Показать платежи"
    >
        <i class="fas fa-money-bill"></i>
    </a>

    <a
        href="{{ route("admin.responses.index", ["user_id" => $data->id]) }}"
        class="btn btn-success btn-sm"
        data-bs-toggle="tooltip"
        title="Показать отклики"
    >
        <i class="fas fa-comments"></i>
    </a>

    @can('user_edit')
        <a
            href="{{ route("admin.users.edit", $data) }}"
            class="btn btn-warning btn-sm"
            data-bs-toggle="tooltip"
            title="Редактировать"
        >
            <i class="fas fa-edit"></i>
        </a>
    @endcan

    <a
        href="{{ route("admin.users.changePassword", $data) }}"
        class="btn btn-info btn-sm mt-1"
        data-toggle="tooltip"
        title="{{ __("labels.admin.changePassword") }}"
    >
        <i class="fas fa-key"></i>
    </a>

    @if ($data->status != 2 && $data->id != 1)
        <a
            href="{{ route("admin.users.block", $data) }}"
            class="btn btn-danger btn-sm mt-1"
            data-method="PATCH"
            data-token="{{ csrf_token() }}"
            data-toggle="tooltip"
            title="{{ __("labels.admin.block") }}"
            data-confirm="@lang("Are you sure?")"
        >
            <i class="fas fa-ban"></i>
        </a>
    @endif

    @if ($data->status == 2)
        <a
            href="{{ route("admin.users.unblock", $data) }}"
            class="btn btn-info btn-sm mt-1"
            data-method="PATCH"
            data-token="{{ csrf_token() }}"
            data-toggle="tooltip"
            title="{{ __("labels.admin.unblock") }}"
            data-confirm="@lang("Are you sure?")"
        >
            <i class="fas fa-check"></i>
        </a>
    @endif

    @if ($data->id != 1)
        <a
            href="{{ route("admin.users.destroy", $data) }}"
            class="btn btn-danger btn-sm mt-1"
            data-method="DELETE"
            data-token="{{ csrf_token() }}"
            data-toggle="tooltip"
            title="{{ __("labels.admin.delete") }}"
            data-confirm="@lang("Are you sure?")"
        >
            <i class="fas fa-trash-alt"></i>
        </a>
    @endif

    @if ($data->email_verified_at == null)
        <a
            href="{{ route("admin.users.emailConfirmationResend", $data->id) }}"
            class="btn btn-primary btn-sm mt-1"
            data-toggle="tooltip"
            title="@lang("Send confirmation email")"
        >
            <i class="fas fa-envelope"></i>
        </a>
    @endif
</div>
