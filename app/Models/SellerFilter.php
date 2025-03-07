<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerFilter extends Model
{
    use SoftDeletes;

    protected $table = 'seller_filters';

    protected $fillable = [
        'title',
        'type',
        'key',
        'options',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Получить типы фильтров
     */
    public static function getTypes(): array
    {
        return [
            'select' => 'Выпадающий список',
            'input' => 'Текстовое поле',
            'checkbox' => 'Чекбокс',
            'radio' => 'Радио кнопки',
            'range' => 'Диапазон',
            'date' => 'Дата',
        ];
    }
}
