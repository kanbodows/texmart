<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Callback;
use App\Enums\CallbackStatus;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CallbackSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ru_RU');

        // Создаем массив с весами для статусов
        $statuses = [
            CallbackStatus::NEW->value => 30,         // 30% новых
            CallbackStatus::IN_PROGRESS->value => 20, // 20% в обработке
            CallbackStatus::COMPLETED->value => 40,   // 40% завершенных
            CallbackStatus::CANCELED->value => 10     // 10% отмененных
        ];

        // Подготавливаем массив статусов с учетом их весов
        $weightedStatuses = [];
        foreach ($statuses as $status => $weight) {
            for ($i = 0; $i < $weight; $i++) {
                $weightedStatuses[] = $status;
            }
        }

        // Создаем 60 тестовых записей
        for ($i = 0; $i < 60; $i++) {
            $createdAt = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            Callback::create([
                'name' => $faker->name(),
                'phone' => $faker->phoneNumber(),
                'email' => rand(1, 4) > 1 ? $faker->email() : null, // 75% записей с email
                'comment' => rand(1, 3) > 1 ? $faker->text(rand(50, 200)) : null, // 66% записей с комментарием
                'status' => $weightedStatuses[array_rand($weightedStatuses)],
                'created_at' => $createdAt,
                'updated_at' => rand(1, 3) > 1 ? $createdAt->copy()->addMinutes(rand(5, 120)) : $createdAt, // 66% записей были обновлены
            ]);
        }
    }
}
