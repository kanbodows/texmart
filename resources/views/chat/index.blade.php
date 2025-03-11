@extends("frontend.layouts.app")

@push('after-styles')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        .list-group-item:hover {
            background-color: #f8f9fa;
        }
        .avatar img, .avatar div {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }
    </style>
@endpush

@section('content')
<br>
<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Сообщения</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($conversations as $conversation)
                            <a href="{{ route('chat.show', $conversation['user']->id) }}"
                               class="list-group-item list-group-item-action d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar">
                                        @if($conversation['user']->avatar)
                                            <img src="{{ $conversation['user']->avatar }}" alt="{{ $conversation['user']->name }}"
                                                 class="rounded-circle" width="50" height="50">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                 style="width: 50px; height: 50px;">
                                                {{ strtoupper(substr($conversation['user']->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ $conversation['user']->name }}</h6>
                                        <small class="text-muted">{{ $conversation['time']->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 text-truncate" style="max-width: 250px;">
                                        {{ $conversation['last_message'] }}
                                    </p>
                                </div>
                                @if($conversation['unread'] > 0)
                                    <div class="ms-2">
                                        <span class="badge bg-primary rounded-pill">{{ $conversation['unread'] }}</span>
                                    </div>
                                @endif
                            </a>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <p class="mb-0">У вас пока нет сообщений</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<br>
<br>
@endsection

@push('after-scripts')
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
