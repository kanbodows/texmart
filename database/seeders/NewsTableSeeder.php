<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Modules\Post\Models\Post;

class NewsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ru_RU');

        // Создаем 20 тестовых новостей
        $news = [];
        for ($i = 0; $i < 20; $i++) {
            $createdAt = $faker->dateTimeBetween('-3 months', 'now');

            $news[] = [
                'title' => $faker->sentence(rand(6, 10)),
                'slug' => $faker->slug,
                'content' => $faker->realText(1000),
                'status' => $faker->randomElement(['draft', 'published', 'inactive']),
                'meta_title' => $faker->sentence(6),
                'meta_description' => $faker->text(160),
                'meta_keywords' => implode(', ', $faker->words(5)),
                'published_at' => $faker->dateTimeBetween('-2 months', 'now'),
                'created_at' => $createdAt,
                'updated_at' => $faker->dateTimeBetween($createdAt, 'now')
            ];
        }

        // Добавляем несколько фиксированных новостей для тестирования
        $fixedNews = [
            [
                'title' => 'Важное объявление о работе платформы',
                'slug' => 'important-platform-announcement',
                'content' => 'Мы рады сообщить о запуске новых функций на нашей платформе. Теперь пользователи могут...',
                'status' => 'published',
                'meta_title' => 'Важное объявление о работе платформы',
                'meta_description' => 'Узнайте о новых функциях и изменениях в работе платформы',
                'meta_keywords' => 'платформа, обновление, новые функции',
                'published_at' => now()->subDays(1),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1)
            ],
            [
                'title' => 'Результаты работы за первый квартал',
                'slug' => 'first-quarter-results',
                'content' => 'За первый квартал текущего года наша платформа достигла следующих показателей...',
                'status' => 'published',
                'meta_title' => 'Результаты работы за первый квартал',
                'meta_description' => 'Ознакомьтесь с результатами работы платформы за первый квартал',
                'meta_keywords' => 'отчет, статистика, результаты, квартал',
                'published_at' => now()->subDays(5),
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(5)
            ],
            [
                'title' => 'Технические работы на платформе',
                'slug' => 'platform-maintenance',
                'content' => 'В связи с проведением технических работ, некоторые функции могут быть временно недоступны...',
                'status' => 'published',
                'meta_title' => 'Технические работы на платформе',
                'meta_description' => 'Информация о проведении технических работ на платформе',
                'meta_keywords' => 'техработы, обслуживание, платформа',
                'published_at' => now()->addDays(1),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Объединяем случайные и фиксированные новости
        $allNews = array_merge($news, $fixedNews);

        // Вставляем все новости в базу данных используя модель Post
        foreach ($allNews as $newsItem) {
            Post::create($newsItem);
        }

        $this->command->info('Создано ' . count($allNews) . ' новостей');
    }
}
