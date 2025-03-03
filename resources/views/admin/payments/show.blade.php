@extends('admin.layouts.app')

@section('title')
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section('breadcrumbs')
    <x-admin.breadcrumbs>
        <x-admin.breadcrumb-item href="{{ route('admin.payments.index') }}" icon="{{ $module_icon }}">
            {{ __($module_title) }}
        </x-admin.breadcrumb-item>
        <x-admin.breadcrumb-item type="active">
            {{ __($module_action) }}
        </x-admin.breadcrumb-item>
    </x-admin.breadcrumbs>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <h4>Основная информация</h4>
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 200px;">ID платежа:</th>
                            <td>{{ $payment->id }}</td>
                        </tr>
                        <tr>
                            <th>Пользователь:</th>
                            <td>
                                @if($payment->user)
                                    <a href="{{ route('admin.users.edit', $payment->user->id) }}">
                                        {{ $payment->user->name }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Сумма:</th>
                            <td>{{ $payment->formatted_amount }}</td>
                        </tr>
                        <tr>
                            <th>Метод оплаты:</th>
                            <td>{{ $payment->payment_method }}</td>
                        </tr>
                        <tr>
                            <th>Статус:</th>
                            <td>{!! $payment->status_badge !!}</td>
                        </tr>
                        <tr>
                            <th>ID транзакции:</th>
                            <td>{{ $payment->payment_id ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Описание:</th>
                            <td>{{ $payment->description ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Дата создания:</th>
                            <td>{{ $payment->created_at->format('d.m.Y H:i:s') }}</td>
                        </tr>
                    </table>

                    @if($payment->refund_status !== 'none')
                        <h4 class="mt-4">Информация о возврате</h4>
                        <table class="table table-striped">
                            <tr>
                                <th style="width: 200px;">Статус возврата:</th>
                                <td>{{ $payment->refund_status }}</td>
                            </tr>
                            <tr>
                                <th>Причина возврата:</th>
                                <td>{{ $payment->refund_reason ?: '-' }}</td>
                            </tr>
                            @if($payment->refunded_at)
                            <tr>
                                <th>Дата возврата:</th>
                                <td>{{ $payment->refunded_at->format('d.m.Y H:i:s') }}</td>
                            </tr>
                            @endif
                        </table>
                    @endif
                </div>
                <div class="col-4">
                    <h4>Техническая информация</h4>
                    <table class="table table-striped">
                        <tr>
                            <th>IP адрес:</th>
                            <td>{{ $payment->ip_address ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>User Agent:</th>
                            <td style="word-break: break-all;">{{ $payment->user_agent ?: '-' }}</td>
                        </tr>
                    </table>

                    @if($payment->meta_data)
                        <h4 class="mt-4">Дополнительные данные</h4>
                        <div class="card">
                            <div class="card-body">
                                <pre class="mb-0"><code>{{ json_encode($payment->meta_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Назад
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
