<?php

namespace Modules\Post\Enums;

enum PostType: string
{
    case ARTICLE = 'article';
    case NEWS = 'news';
    case STATIC_PAGE = 'static_page';
    case COURSES = 'courses';
    case ADS = 'ads';

    public static function getAllValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getAllNames(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->name;
        }

        return $array;
    }
}
