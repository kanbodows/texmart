<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role;

class FeedbacksTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ru_RU');

        // Проверяем существование роли manufacturer
        $manufacturerRole = Role::firstOrCreate(['name' => 'manufacturer']);

        // Создаем тестового производителя, если нет ни одного
        if (User::role('manufacturer')->count() === 0) {
            $manufacturer = User::create([
                'name' => 'Тестовый Производитель',
                'email' => 'test.manufacturer2@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $manufacturer->assignRole('manufacturer');
            echo "Создан тестовый производитель\n";
        }

        // Создаем тестового пользователя, если нет обычных пользователей
        $regularUsers = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'manufacturer');
        });

        if ($regularUsers->count() === 0) {
            $user = User::create([
                'name' => 'Тестовый Пользователь',
                'email' => 'test.user@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            echo "Создан тестовый пользователь\n";
        }

        // Получаем ID пользователей с ролью manufacturer
        $manufacturerIds = User::role('manufacturer')->pluck('id')->toArray();

        // Получаем ID обычных пользователей (не производителей)
        $userIds = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'manufacturer');
        })->pluck('id')->toArray();

        // Если нет пользователей, прерываем выполнение
        if (empty($manufacturerIds) || empty($userIds)) {
            echo "Ошибка: не удалось получить необходимых пользователей\n";
            return;
        }

        // Создаем 20 тестовых отзывов
        $feedbacks = [];
        for ($i = 0; $i < 20; $i++) {
            $feedbacks[] = [
                'feedback' => $faker->realText(rand(100, 500)),
                'rating' => rand(1, 5),
                'user_id' => $faker->randomElement($userIds),
                'manufacture_user_id' => $faker->randomElement($manufacturerIds),
                'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
                'updated_at' => now()
            ];
        }

        // Добавляем несколько фиксированных отзывов для тестирования
        $fixedFeedbacks = [
            [
                'feedback' => 'Отличный производитель! Быстро отвечает на сообщения и предлагает хорошие условия.',
                'rating' => 5,
                'user_id' => $userIds[0],
                'manufacture_user_id' => $manufacturerIds[0],
                'created_at' => now()->subDays(5),
                'updated_at' => now()
            ],
            [
                'feedback' => 'Не очень доволен сотрудничеством. Долго ждал ответа.',
                'rating' => 2,
                'user_id' => $userIds[0],
                'manufacture_user_id' => $manufacturerIds[0],
                'created_at' => now()->subDays(3),
                'updated_at' => now()
            ],
            [
                'feedback' => 'Нормальный производитель, среднее качество обслуживания.',
                'rating' => 3,
                'user_id' => $userIds[1] ?? $userIds[0],
                'manufacture_user_id' => $manufacturerIds[0],
                'created_at' => now()->subDays(1),
                'updated_at' => now()
            ]
        ];

        DB::table('feedbacks')->insert(array_merge($feedbacks, $fixedFeedbacks));
        echo "Создано " . (count($feedbacks) + count($fixedFeedbacks)) . " отзывов\n";
    }
}
