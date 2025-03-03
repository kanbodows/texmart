<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Announce;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ResponsesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ru_RU');

        // Получаем ID объявлений
        $announceIds = Announce::pluck('id')->toArray();

        // Получаем ID пользователей (не производителей)
        $userIds = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'manufacturer');
        })->pluck('id')->toArray();

        // Если нет необходимых данных, прерываем выполнение
        if (empty($announceIds) || empty($userIds)) {
            echo "Нет необходимых данных для создания откликов\n";
            return;
        }

        // Создаем 30 тестовых откликов
        $responses = [];
        for ($i = 0; $i < 30; $i++) {
            $responses[] = [
                'announce_id' => $faker->randomElement($announceIds),
                'user_id' => $faker->randomElement($userIds),
                'message' => $faker->realText(rand(100, 300)),
                'price' => rand(1000, 50000),
                'status' => $faker->randomElement(['new', 'viewed', 'accepted', 'rejected']),
                'created_at' => $faker->dateTimeBetween('-2 months', 'now'),
                'updated_at' => now()
            ];
        }

        // Добавляем несколько фиксированных откликов для тестирования
        if (!empty($announceIds) && !empty($userIds)) {
            $fixedResponses = [
                [
                    'announce_id' => $announceIds[0],
                    'user_id' => $userIds[0],
                    'message' => 'Готов выполнить заказ в кратчайшие сроки. Есть большой опыт в подобных проектах.',
                    'price' => 15000,
                    'status' => 'new',
                    'created_at' => now()->subDays(5),
                    'updated_at' => now()
                ],
                [
                    'announce_id' => $announceIds[0],
                    'user_id' => $userIds[0],
                    'message' => 'Предлагаю выгодные условия сотрудничества. Могу начать работу немедленно.',
                    'price' => 25000,
                    'status' => 'accepted',
                    'created_at' => now()->subDays(3),
                    'updated_at' => now()
                ],
                [
                    'announce_id' => $announceIds[0],
                    'user_id' => $userIds[1] ?? $userIds[0],
                    'message' => 'Имею все необходимое оборудование. Гарантирую качество и соблюдение сроков.',
                    'price' => 35000,
                    'status' => 'rejected',
                    'created_at' => now()->subDays(1),
                    'updated_at' => now()
                ]
            ];

            $responses = array_merge($responses, $fixedResponses);
        }

        // Создаем тестовое объявление, если нет ни одного
        if (empty($announceIds)) {
            $manufacturerId = User::role('manufacturer')->first()->id ?? null;

            if ($manufacturerId) {
                $announce = Announce::create([
                    'title' => 'Тестовое объявление для откликов',
                    'description' => $faker->realText(500),
                    'user_id' => $manufacturerId,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Добавляем отклики для созданного объявления
                $responses[] = [
                    'announce_id' => $announce->id,
                    'user_id' => $userIds[0],
                    'message' => 'Отклик на тестовое объявление',
                    'price' => 20000,
                    'status' => 'new',
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                echo "Создано тестовое объявление\n";
            }
        }

        DB::table('responses')->insert($responses);
        echo "Создано " . count($responses) . " откликов\n";
    }
}
