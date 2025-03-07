<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filter;

class FiltersTableSeeder extends Seeder
{
    public function run(): void
    {
        $filters = [
            // Категории
            [
                'name' => 'Сырье лекало',
                'filter_key' => 'category'
            ],
            [
                'name' => 'Крой',
                'filter_key' => 'category'
            ],
            [
                'name' => 'Спец машинки',
                'filter_key' => 'category'
            ],
            [
                'name' => 'Надомницы',
                'filter_key' => 'category'
            ],
            [
                'name' => 'Утюг, упаковка',
                'filter_key' => 'category'
            ],
            [
                'name' => 'Услуги маркировки (Фулфилмент)',
                'filter_key' => 'category'
            ],
            [
                'name' => 'Карго (перевозка)',
                'filter_key' => 'category'
            ],

            // Пол
            [
                'name' => 'Женский',
                'filter_key' => 'gender'
            ],
            [
                'name' => 'Мужской',
                'filter_key' => 'gender'
            ],
            [
                'name' => 'Детский',
                'filter_key' => 'gender'
            ],
            [
                'name' => 'Другое',
                'filter_key' => 'gender'
            ],

            // Масштаб
            [
                'name' => 'Мелкий (до 10 человек)',
                'filter_key' => 'scale'
            ],
            [
                'name' => 'Средний (до 25 человек)',
                'filter_key' => 'scale'
            ],
            [
                'name' => 'Крупный (до 100 человек)',
                'filter_key' => 'scale'
            ],
            [
                'name' => 'Очень крупный (от 100 человек)',
                'filter_key' => 'scale'
            ],

            // Слои
            [
                'name' => '1-й слой (трусы, майки, носки)',
                'filter_key' => 'layer'
            ],
            [
                'name' => '2-й слой (футболки, брюки, платья и т.д.)',
                'filter_key' => 'layer'
            ],
            [
                'name' => '3-й слой (куртки, пальто и т.д.)',
                'filter_key' => 'layer'
            ],
            [
                'name' => 'Другое (полотенца, постельное белье и т.д.)',
                'filter_key' => 'layer'
            ],
        ];

        foreach ($filters as $filter) {
            Filter::create($filter);
        }

        $this->command->info('Создано ' . count($filters) . ' фильтров');
    }
}
