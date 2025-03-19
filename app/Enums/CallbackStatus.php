<?php

namespace App\Enums;

enum CallbackStatus: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match($this) {
            self::NEW => 'Новый',
            self::IN_PROGRESS => 'В обработке',
            self::COMPLETED => 'Завершен',
            self::CANCELED => 'Отменен',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::NEW => 'warning',
            self::IN_PROGRESS => 'info',
            self::COMPLETED => 'success',
            self::CANCELED => 'danger',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
