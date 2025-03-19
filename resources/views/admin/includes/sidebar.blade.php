<?php
$notifications = optional(auth()->user())->unreadNotifications;
$notifications_count = optional($notifications)->count();
$notifications_latest = optional($notifications)->take(5);
?>

<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
    <div class="sidebar-header border-bottom">
        <div class="sidebar-brand d-sm-flex justify-content-center" style="margin: auto;">
            <a href="/">
                <img
                    class="sidebar-brand-full"
                    src="{{ asset("img/logo2.png") }}"
                    alt="{{ app_name() }}"
                    height="46"
                />
                <img
                    class="sidebar-brand-narrow"
                    src="{{ asset("img/logo2.png") }}"
                    alt="{{ app_name() }}"
                    height="46"
                />
            </a>
        </div>
        <button
            class="btn-close d-lg-none"
            data-coreui-dismiss="offcanvas"
            data-coreui-theme="dark"
            type="button"
            aria-label="Close"
            onclick='coreui.Sidebar.getInstance(document.querySelector("#sidebar")).toggle()'
        ></button>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
        <li class="nav-item">
            <a class="nav-link" href="{{ route("admin.dashboard") }}">
                <i class="nav-icon fa-solid fa-cubes"></i>
                &nbsp;
                @lang("Dashboard")
            </a>
        </li>

        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <i class="nav-icon fa-solid fa-users"></i>
                &nbsp;
                Пользователи
            </a>
            <ul class="nav-group-items compact">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.users.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Пользователи
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.roles.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Роли
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <i class="nav-icon fa-solid fa-money-bill"></i>
                &nbsp;
                Финансы
            </a>
            <ul class="nav-group-items compact">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.payments.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Платежи
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <i class="nav-icon fa-solid fa-bullhorn"></i>
                &nbsp;
                Объявления
            </a>
            <ul class="nav-group-items compact">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.announces.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Объявления
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.responses.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Отклики
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.feedbacks.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Отзывы
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.messages.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Сообщения
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.callbacks.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Обратные звонки
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <i class="nav-icon fa-solid fa-file-lines"></i>
                &nbsp;
                Контент
            </a>
            <ul class="nav-group-items compact">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.courses.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        База знаний
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.posts.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Новости
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.pages.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Статические страницы
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.ads.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Рекламные баннеры
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <i class="nav-icon fa-solid fa-cogs"></i>
                &nbsp;
                Настройки
            </a>
            <ul class="nav-group-items compact">
                <li class="nav-item">
                    <a class="nav-link" href="/admin/sellers_conf">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Настройки производителя
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.settings.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Общие настройки
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <i class="nav-icon fa-solid fa-server"></i>
                &nbsp;
                Система
            </a>
            <ul class="nav-group-items compact">
                @can("view_logs")
                <li class="nav-group">
                    <a class="nav-link nav-group-toggle" href="#">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Логи
                    </a>
                    <ul class="nav-group-items">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("log-viewer::dashboard") }}">
                                <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                                Панель логов
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route("log-viewer::logs.list") }}">
                                <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                                Ежедневные логи
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/telescope">
                                <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                                Telescope
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/pulse">
                                <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                                Pulse
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.backups.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                        Резервные копии
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" data-coreui-toggle="unfoldable" type="button"></button>
    </div>
</div>
