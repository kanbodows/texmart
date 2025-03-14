@extends('admin.layouts.app')

@push('after-styles')
<style>
.chat-message {
    margin-bottom: 1rem;
    padding: 0.5rem;
    border-radius: 0.25rem;
    position: relative;
}
.chat-message.from {
    background-color: #f8f9fa;
    margin-right: 20%;
}
.chat-message.to {
    background-color: #e9ecef;
    margin-left: 20%;
}
.chat-message .meta {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}
.chat-message .actions {
    position: absolute;
    right: 0.5rem;
    top: 0.5rem;
    display: none;
}
.chat-message:hover .actions {
    display: block;
}
</style>
@endpush

@push('after-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/ru.min.js"></script>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <x-admin.section-header
                :module_name="$module_name"
                :module_title="$module_title"
                :module_icon="$module_icon"
                :module_action="$module_action"
                :filters_block=1
                :add_button=0
                :trash_button=0
            />

            {{-- Фильтры --}}
            <div class="collapse mb-4" id="filters-collapse">
                <div class="card card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select class="form-select filter select2-from-user" data-column="from_user_id">
                                <option value="">Участник чата</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control filter" data-column="date_from" placeholder="Дата от">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control filter" data-column="date_to" placeholder="Дата до">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table-bordered table-hover table" id="datatable">
                            <thead>
                                <tr>
                                    <th>Участники чата</th>
                                    <th>Последнее сообщение</th>
                                    <th>Файл</th>
                                    <th>Статус</th>
                                    <th>Дата</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Модальное окно для просмотра чата --}}
    <div class="modal fade" id="messageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">История переписки</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="chat-history" id="chat-history">
                        <!-- История чата будет загружена сюда -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
<script>
$(document).ready(function() {
    // Устанавливаем русскую локаль для moment
    moment.locale('ru');

    // Инициализация Select2 для пользователей
    initSelect2('.select2-from-user', {
        placeholder: 'Выберите пользователя',
        ajax: {
            url: '{{ route("admin.users.index_list") }}'
        }
    });

    // Инициализация таблицы
    const table = initDataTable({
        ajax: {
            url: '{{ route("admin.messages.index_data") }}',
            data: function(d) {
                $('.filter').each(function() {
                    d[$(this).data('column')] = $(this).val();
                });
            }
        },
        columns: [
            {
                data: null,
                name: 'from_user',
                render: function(data) {
                    return data.from_user + ' ⟷ ' + data.to_user;
                },
                orderable: false,
            },
            {data: 'message', name: 'message'},
            {data: 'file', name: 'file'},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at'}
        ],
        createdRow: function(row, data) {
            $(row).attr('data-id', data.id)
                 .attr('data-from-user-id', data.from_user_id)
                 .attr('data-to-user-id', data.to_user_id);
        }
    });

    // Инициализация фильтров
    initFilters(table, {
        select2Selectors: ['.select2-from-user']
    });

    // Функция для загрузки истории чата
    function loadChatHistory(fromUserId, toUserId) {
        $.ajax({
            url: '{{ route("admin.messages.show", "") }}/' + fromUserId,
            method: 'GET',
            data: {
                from_user_id: fromUserId,
                to_user_id: toUserId
            },
            success: function(response) {
                if (response.success) {
                    const messages = response.data;
                    let html = '';

                    messages.forEach(message => {
                        const formattedDate = moment(message.created_at).format('DD.MM.YYYY HH:mm');
                        html += `
                            <div class="chat-message ${message.from_user_id == fromUserId ? 'from' : 'to'}" data-id="${message.id}">
                                <div class="actions">
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-message" title="Удалить сообщение">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                <div class="meta">
                                    <strong>${message.from_user.name}</strong> •
                                    ${formattedDate}
                                </div>
                                <div class="content">
                                    ${message.message}
                                    ${message.file ? `
                                        <div class="mt-2">
                                            <a href="/${message.file_path}" target="_blank">
                                                <i class="fas fa-file"></i> ${message.file_name}
                                            </a>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    });

                    $('#chat-history').html(html);
                    $('#messageModal').modal('show');

                    // Инициализация обработчиков удаления сообщений
                    initMessageDeleteHandlers();
                }
            }
        });
    }

    // Функция для инициализации обработчиков удаления сообщений
    function initMessageDeleteHandlers() {
        $('.delete-message').click(function(e) {
            e.stopPropagation();
            const $message = $(this).closest('.chat-message');
            const messageId = $message.data('id');

            if (confirm('Вы уверены, что хотите удалить это сообщение?')) {
                $.ajax({
                    url: '{{ route("admin.messages.destroy", "") }}/' + messageId,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            $message.fadeOut(300, function() {
                                $(this).remove();
                                // Обновляем таблицу чатов
                                table.ajax.reload();
                            });
                            toastr.success('Сообщение успешно удалено');
                        }
                    }
                });
            }
        });
    }

    // Инициализация контекстного меню
    initContextMenu(table, {
        items: {
            history: {
                name: "История чата",
                icon: "fas fa-history",
                callback: function(key, options) {
                    const $row = $(options.$trigger);
                    const fromUserId = $row.data('from-user-id');
                    const toUserId = $row.data('to-user-id');
                    if (fromUserId && toUserId) {
                        loadChatHistory(fromUserId, toUserId);
                    }
                }
            },
            "sep1": "---------",
            delete: {
                name: "Удалить чат",
                icon: "fas fa-trash-alt",
                callback: function(key, options) {
                    const $row = $(options.$trigger);
                    if (confirm('Вы уверены, что хотите удалить весь чат?')) {
                        // Здесь можно добавить удаление всего чата
                        toastr.warning('Функция удаления всего чата пока не реализована');
                    }
                }
            }
        }
    });
});
</script>
@endpush
