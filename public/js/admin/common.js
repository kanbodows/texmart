/**
 * Универсальная инициализация Select2
 */
function initSelect2(selector, options = {}) {
    const defaultOptions = {
        placeholder: 'Выберите значение',
        allowClear: true,
        ajax: {
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        }
    };

    $(selector).select2({...defaultOptions, ...options});
}

/**
 * Инициализация фильтров и их обработчиков
 */
function initFilters(table, options = {}) {
    const defaultOptions = {
        filterSelector: '.filter',
        resetSelector: '#reset-filters',
        select2Selectors: []
    };

    const settings = {...defaultOptions, ...options};

    // Обработка изменений в фильтрах
    let timer;
    $(settings.filterSelector).on('change keyup select2:select select2:unselect', function() {
        clearTimeout(timer);
        timer = setTimeout(function() {
            table.draw();
        }, 500);
    });

    // Сброс фильтров
    $(settings.resetSelector).on('click', function() {
        $(settings.filterSelector).val('');
        settings.select2Selectors.forEach(selector => {
            $(selector).val(null).trigger('change');
        });
        table.draw();
    });
}

/**
 * Автоматический выбор пользователя из URL
 */
function autoSelectUserFromUrl(table) {
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('user_id');
    if (userId) {
        $.ajax({
            url: '/admin/users/index_list',
            data: {
                id: userId
            },
            dataType: 'json'
        }).then(function(data) {
            if (data.items.length > 0) {
                const user = data.items[0];
                const option = new Option(user.text, user.id, true, true);
                $('.select2-user').append(option).trigger('change');
                table.draw();
            }
        });
    }
}

/**
 * Инициализация базовой DataTable
 */
function initDataTable(options, selector = '#datatable') {
    const defaultOptions = {
        processing: true,
        serverSide: true,
        responsive: true,
        searching: false,
        pageLength: 50,
        order: [[0, 'desc']],
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>',
            search: 'Поиск:',
            lengthMenu: 'Показать _MENU_ записей',
            info: 'Записи с _START_ до _END_ из _TOTAL_ записей',
            infoEmpty: 'Записи с 0 до 0 из 0 записей',
            infoFiltered: '(отфильтровано из _MAX_ записей)',
            loadingRecords: 'Загрузка записей...',
            zeroRecords: 'Записи отсутствуют.',
            emptyTable: 'В таблице отсутствуют данные',
            paginate: {
                first: '«',
                previous: '‹',
                next: '›',
                last: '»'
            },
            aria: {
                sortAscending: ': активировать для сортировки столбца по возрастанию',
                sortDescending: ': активировать для сортировки столбца по убыванию'
            }
        },
    };

    return $(selector).DataTable({...defaultOptions, ...options});
}

class DatatableModal {
    constructor(options) {
        this.options = {
            modalId: null,
            buttonSelector: null,
            url: null,
            columns: [],
            ...options
        };

        this.table = null;
        this.init();
    }

    init() {
        $(document).on('click', this.options.buttonSelector, (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data('announce-id');
            this.openModal(id);
        });

        $(`#${this.options.modalId}`).on('hidden.bs.modal', () => {
            if (this.table) {
                this.table.destroy();
                this.table = null;
            }
        });
    }

    openModal(id) {
        if (this.table) {
            this.table.destroy();
        }

        this.table = $(`#${this.options.modalId}_table`).DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: `${this.options.url}/${id}`,
            columns: this.options.columns,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.2.2/i18n/ru.json'
            }
        });

        $(`#${this.options.modalId}`).modal('show');
    }
}
