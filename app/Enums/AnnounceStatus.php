<?php

namespace App\Enums;

enum AnnounceStatus: string
{
    case DRAFT = 'draft';
    case MODERATION = 'moderation';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Черновик',
            self::MODERATION => 'На модерации',
            self::ACTIVE => 'Активно',
            self::INACTIVE => 'Неактивно',
            self::REJECTED => 'Отклонено',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'secondary',
            self::MODERATION => 'info',
            self::ACTIVE => 'success',
            self::INACTIVE => 'warning',
            self::REJECTED => 'danger',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
