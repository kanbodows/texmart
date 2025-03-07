/**
 * Общие настройки для контекстных меню в админке
 */
const ContextMenuConfig = {
    // Стандартные иконки
    icons: {
        view: 'fas fa-eye',
        edit: 'fas fa-edit',
        delete: 'fas fa-trash',
        status: 'fas fa-toggle-on',
        block: 'fas fa-ban',
        unblock: 'fas fa-check',
        comments: 'fas fa-comments',
        payments: 'fas fa-money-bill',
        settings: 'fas fa-cog',
        approve: 'fas fa-check-circle',
        reject: 'fas fa-ban'
    },

    // Стандартные тексты подтверждений
    confirms: {
        delete: 'Вы действительно хотите удалить этот элемент?',
        block: 'Вы действительно хотите заблокировать этого пользователя?',
        unblock: 'Вы действительно хотите разблокировать этого пользователя?',
        status: 'Вы действительно хотите изменить статус?',
        approve: 'Вы действительно хотите одобрить этот элемент?',
        reject: 'Вы действительно хотите отклонить этот элемент?'
    },

    // Стандартные названия действий
    labels: {
        view: 'Просмотр',
        edit: 'Редактировать',
        delete: 'Удалить',
        status: 'Статус',
        block: 'Заблокировать',
        unblock: 'Разблокировать',
        comments: 'Комментарии',
        payments: 'Платежи',
        settings: 'Настройки',
        approve: 'Одобрить',
        reject: 'Отклонить',
        actions: 'Действия'
    },

    // Стандартные классы для кнопок
    buttonClasses: {
        view: 'btn-info',
        edit: 'btn-warning',
        delete: 'btn-danger',
        status: 'btn-primary',
        block: 'btn-danger',
        unblock: 'btn-success',
        approve: 'btn-success',
        reject: 'btn-danger',
        default: 'btn-secondary'
    },

    // Базовые пункты меню
    getBaseItems() {
        return {
            view: {
                name: this.labels.view,
                icon: this.icons.view
            },
            edit: {
                name: this.labels.edit,
                icon: this.icons.edit
            },
            sep1: "---------",
            delete: {
                name: this.labels.delete,
                icon: this.icons.delete,
                className: 'context-menu-item-danger'
            }
        };
    },

    // Пункты меню для статусов
    getStatusItems() {
        return {
            status: {
                name: this.labels.status,
                icon: this.icons.status,
                items: {
                    approve: {
                        name: this.labels.approve,
                        icon: this.icons.approve
                    },
                    reject: {
                        name: this.labels.reject,
                        icon: this.icons.reject
                    }
                }
            }
        };
    }
};
