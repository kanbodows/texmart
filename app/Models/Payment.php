<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'payment_id',
        'description',
        'meta_data',
        'ip_address',
        'user_agent',
        'refund_status',
        'refund_reason',
        'refunded_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'meta_data' => 'array',
        'refunded_at' => 'datetime',
    ];

    // Статусы платежей
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';

    // Статусы возвратов
    const REFUND_NONE = 'none';
    const REFUND_PENDING = 'pending';
    const REFUND_COMPLETED = 'completed';
    const REFUND_FAILED = 'failed';

    // Методы оплаты
    const METHOD_CARD = 'card';
    const METHOD_BANK = 'bank_transfer';
    const METHOD_CRYPTO = 'crypto';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            self::STATUS_COMPLETED => '<span class="badge bg-success">Выполнен</span>',
            self::STATUS_PENDING => '<span class="badge bg-warning">В обработке</span>',
            self::STATUS_FAILED => '<span class="badge bg-danger">Ошибка</span>',
            self::STATUS_REFUNDED => '<span class="badge bg-info">Возвращен</span>',
            default => '<span class="badge bg-secondary">Неизвестно</span>'
        };
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2, '.', ' ') . ' ' . $this->currency;
    }
}
