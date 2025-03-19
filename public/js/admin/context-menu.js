// Дефолтные колбеки для стандартных действий
const defaultCallbacks = {
    view: function(id, table) {
        window.location.href = window.location.pathname + '/' + id;
    },
    edit: function(id, table) {
        window.location.href = window.location.pathname + '/' + id + '/edit';
    },
    delete: function(id, table) {
        if (confirm('Вы уверены?')) {
            $.ajax({
                url: window.location.pathname + '/' + id,
                type: 'DELETE',
                success: function() {
                    table.ajax.reload();
                },
                error: function(xhr) {
                    console.error('Ошибка при удалении:', xhr);
                    alert('Произошла ошибка при удалении');
                }
            });
        }
    }
};

/**
 * Инициализация контекстного меню для DataTables
 * @param {object} table - инстанс DataTable
 * @param {object} options - настройки меню
 */
function initContextMenu(table, options = {}) {
    // Дефолтные настройки для стандартных пунктов меню
    const defaultItems = {
        edit: {
            name: "Редактировать",
            icon: "fas fa-edit"
        },
        view: {
            name: "Просмотр",
            icon: "fas fa-eye"
        },
        delete: {
            name: "Удалить",
            icon: "fas fa-trash",
            className: 'context-menu-item-danger'
        }
    };

    // Подсветка выбранной строки
    $('#' + table.table().node().id + ' tbody').on('contextmenu', 'tr', function() {
        $(this).siblings().removeClass('selected-row');
        $(this).addClass('selected-row');
    });

    // Снятие подсветки при клике вне таблицы
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#' + table.table().node().id).length) {
            $('#' + table.table().node().id + ' tbody tr').removeClass('selected-row');
        }
    });

    // Объединяем пользовательские items с дефолтными настройками
    if (options.items) {
        Object.keys(options.items).forEach(key => {
            if (defaultItems[key]) {
                options.items[key] = {
                    ...defaultItems[key],
                    ...options.items[key]
                };
            }
        });
    }

    // Инициализация контекстного меню
    $.contextMenu({
        selector: '#' + table.table().node().id + ' tbody tr',
        callback: function(key, opt) {
            const id = $(this).data('id');

            // Если есть кастомный колбек в options, используем его
            if (options.callback) {
                options.callback.call(this, key, opt);
                return;
            }

            // Иначе используем дефолтный колбек если он есть
            if (defaultCallbacks[key]) {
                defaultCallbacks[key](id, table);
            }
        },
        items: options.items || defaultItems,
        events: {
            show: function(options) {
                // Обновляем состояние пунктов меню
                if (typeof options.updateItems === 'function') {
                    options.updateItems(this);
                }
            }
        }
    });
}

/**
 * Обновление полей модели через Ajax
 * @param {string} module - название модуля
 * @param {number} id - ID записи
 * @param {object} fields - объект с полями для обновления
 * @param {object} table - объект DataTable для обновления
 * @param {string} successMessage - сообщение об успешном обновлении
 */
function updateModelFields(module, id, fields, table, successMessage = 'Данные обновлены') {
    $.ajax({
        url: `/admin/${module}/${id}/ajax-update`,
        type: 'PATCH',
        data: {
            fields: fields
        },
        success: function(response) {
            if (response.success) {
                // toastr.success(successMessage);
                if (table) {
                    table.ajax.reload();
                }
            }
        },
        error: function(xhr) {
            // toastr.error('Ошибка при обновлении данных');
            alert('Ошибка при обновлении данных');
            console.error(xhr);
        }
    });
}
