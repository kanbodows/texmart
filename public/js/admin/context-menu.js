/**
 * Инициализация контекстного меню для DataTables
 * @param {object} table - инстанс DataTable
 * @param {object} options - настройки меню
 */
function initContextMenu(table, options = {}) {
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

    // Инициализация контекстного меню
    $.contextMenu({
        selector: '#' + table.table().node().id + ' tbody tr',
        callback: options.callback || function() {},
        items: options.items || {},
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
