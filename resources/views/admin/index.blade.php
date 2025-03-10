@extends("admin.layouts.app")

@section("title")
    {{ __($module_action) }}
@endsection

@section("breadcrumbs")
    <x-admin.breadcrumbs>
        <x-admin.breadcrumb-item type="active" icon="fas fa-tachometer-alt">
            {{ __($module_action) }}
        </x-admin.breadcrumb-item>
    </x-admin.breadcrumbs>
@endsection

@section("content")
    <!-- Быстрые действия -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Быстрые действия</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.announces.index', ['status' => App\Enums\AnnounceStatus::MODERATION->value]) }}" class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                                <i class="fas fa-tasks fa-2x mb-2"></i>
                                <span>Модерация объявлений</span>
                                @if($stats['announces']['moderation']['count'] > 0)
                                    <span class="badge bg-danger mt-2">{{ $stats['announces']['moderation']['count'] }}</span>
                                @endif
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                                <i class="fas fa-user-plus fa-2x mb-2"></i>
                                <span>Создать пользователя</span>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.responses.index', ['status' => 'pending']) }}" class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                                <i class="fas fa-comments fa-2x mb-2"></i>
                                <span>Необработанные отклики</span>
                                @if($stats['responses']['pending']['count'] > 0)
                                    <span class="badge bg-danger mt-2">{{ $stats['responses']['pending']['count'] }}</span>
                                @endif
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                                <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                <span>Ожидающие платежи</span>
                                @if($stats['payments']['pending']['count'] > 0)
                                    <span class="badge bg-danger mt-2">{{ $stats['payments']['pending']['count'] }}</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Объявления -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Объявления</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($stats['announces'] as $key => $stat)
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-{{ $stat['color'] }} p-2">
                                            <i class="fas {{ $stat['icon'] }} fa-fw"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="small text-muted">{{ $stat['label'] }}</div>
                                        <div class="fs-5 fw-bold">{{ number_format($stat['count'], 0, '.', ' ') }}</div>
                                        @if(isset($stat['change']))
                                            <small class="text-{{ $stat['change'] > 0 ? 'success' : ($stat['change'] < 0 ? 'danger' : 'muted') }}">
                                                <i class="fas fa-{{ $stat['change'] > 0 ? 'arrow-up' : ($stat['change'] < 0 ? 'arrow-down' : 'equals') }}"></i>
                                                {{ abs($stat['change']) }}% за неделю
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Пользователи -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Пользователи</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($stats['users'] as $key => $stat)
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-{{ $stat['color'] }} p-2">
                                            <i class="fas {{ $stat['icon'] }} fa-fw"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="small text-muted">{{ $stat['label'] }}</div>
                                        <div class="fs-5 fw-bold">{{ number_format($stat['count'], 0, '.', ' ') }}</div>
                                        @if(isset($stat['change']))
                                            <small class="text-{{ $stat['change'] > 0 ? 'success' : ($stat['change'] < 0 ? 'danger' : 'muted') }}">
                                                <i class="fas fa-{{ $stat['change'] > 0 ? 'arrow-up' : ($stat['change'] < 0 ? 'arrow-down' : 'equals') }}"></i>
                                                {{ abs($stat['change']) }}% за неделю
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Отклики -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Отклики</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($stats['responses'] as $key => $stat)
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-{{ $stat['color'] }} p-2">
                                            <i class="fas {{ $stat['icon'] }} fa-fw"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="small text-muted">{{ $stat['label'] }}</div>
                                        <div class="fs-5 fw-bold">{{ number_format($stat['count'], 0, '.', ' ') }}</div>
                                        @if(isset($stat['change']))
                                            <small class="text-{{ $stat['change'] > 0 ? 'success' : ($stat['change'] < 0 ? 'danger' : 'muted') }}">
                                                <i class="fas fa-{{ $stat['change'] > 0 ? 'arrow-up' : ($stat['change'] < 0 ? 'arrow-down' : 'equals') }}"></i>
                                                {{ abs($stat['change']) }}% за неделю
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Платежи -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Платежи</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($stats['payments'] as $key => $stat)
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-{{ $stat['color'] }} p-2">
                                            <i class="fas {{ $stat['icon'] }} fa-fw"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="small text-muted">{{ $stat['label'] }}</div>
                                        <div class="fs-5 fw-bold">
                                            @if(isset($stat['is_money']))
                                                {{ number_format($stat['count'], 0, '.', ' ') }} ₽
                                            @else
                                                {{ number_format($stat['count'], 0, '.', ' ') }}
                                            @endif
                                        </div>
                                        @if(isset($stat['change']))
                                            <small class="text-{{ $stat['change'] > 0 ? 'success' : ($stat['change'] < 0 ? 'danger' : 'muted') }}">
                                                <i class="fas fa-{{ $stat['change'] > 0 ? 'arrow-up' : ($stat['change'] < 0 ? 'arrow-down' : 'equals') }}"></i>
                                                {{ abs($stat['change']) }}% за месяц
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Графики -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Регистрации пользователей</h5>
                </div>
                <div class="card-body">
                    <canvas id="userRegistrationsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Статусы объявлений</h5>
                </div>
                <div class="card-body">
                    <canvas id="announceStatusesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Активность за месяц</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyActivityChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Популярные категории</h5>
                </div>
                <div class="card-body">
                    <canvas id="popularCategoriesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-styles')
    <link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.min.css" rel="stylesheet">
@endpush

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // График регистраций пользователей
            new Chart(document.getElementById('userRegistrationsChart'), {
                type: 'line',
                data: {
                    labels: @json($charts['userRegistrations']['labels']),
                    datasets: [{
                        label: 'Регистрации',
                        data: @json($charts['userRegistrations']['data']),
                        borderColor: '#0d6efd',
                        tension: 0.3,
                        fill: true,
                        backgroundColor: 'rgba(13, 110, 253, 0.1)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Круговая диаграмма статусов объявлений
            new Chart(document.getElementById('announceStatusesChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($charts['announceStatuses']['labels']),
                    datasets: [{
                        data: @json($charts['announceStatuses']['data']),
                        backgroundColor: @json($charts['announceStatuses']['colors'])
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });

            // График активности
            new Chart(document.getElementById('monthlyActivityChart'), {
                type: 'line',
                data: {
                    labels: @json($charts['monthlyActivity']['labels']),
                    datasets: [{
                        label: 'Объявления',
                        data: @json($charts['monthlyActivity']['announces']),
                        borderColor: '#0d6efd',
                        tension: 0.3
                    }, {
                        label: 'Отклики',
                        data: @json($charts['monthlyActivity']['responses']),
                        borderColor: '#198754',
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Диаграмма популярных категорий
            new Chart(document.getElementById('popularCategoriesChart'), {
                type: 'bar',
                data: {
                    labels: @json($charts['popularCategories']['labels']),
                    datasets: [{
                        label: 'Объявления',
                        data: @json($charts['popularCategories']['data']),
                        backgroundColor: @json($charts['popularCategories']['colors'])
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
