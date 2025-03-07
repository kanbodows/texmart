<?php

namespace App\Enums;

enum UserStatus: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;
    case BLOCKED = 2;

    public function label(): string
    {
        return match($this) {
            self::INACTIVE => '<span class="badge bg-danger">Неактивный</span>',
            self::ACTIVE => '<span class="badge bg-success">Активный</span>',
            self::BLOCKED => '<span class="badge bg-warning">Заблокирован</span>',
        };
    }

    public function name(): string
    {
        return match($this) {
            self::INACTIVE => 'Неактивный',
            self::ACTIVE => 'Активный',
            self::BLOCKED => 'Заблокирован',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }
}
