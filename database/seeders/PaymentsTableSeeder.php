<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\User;
use Faker\Factory as Faker;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ru_RU');
        $users = User::pluck('id')->toArray();
        $statuses = ['completed', 'pending', 'failed', 'refunded'];
        $methods = ['card', 'bank_transfer', 'crypto'];
        $currencies = ['KZT', 'RUB', 'USD'];

        $payments = [
            [
                'user_id' => $users[array_rand($users)],
                'amount' => 15000,
                'currency' => 'KZT',
                'status' => 'completed',
                'payment_method' => 'card',
                'payment_id' => 'PAY-' . $faker->unique()->randomNumber(8),
                'description' => 'Пополнение баланса',
                'ip_address' => $faker->ipv4,
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                'created_at' => now()->subDays(1),
            ],
            [
                'user_id' => $users[array_rand($users)],
                'amount' => 25000,
                'currency' => 'RUB',
                'status' => 'pending',
                'payment_method' => 'bank_transfer',
                'payment_id' => 'PAY-' . $faker->unique()->randomNumber(8),
                'description' => 'Пополнение баланса через банк',
                'ip_address' => $faker->ipv4,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'created_at' => now()->subHours(12),
            ],
            [
                'user_id' => $users[array_rand($users)],
                'amount' => 50000,
                'currency' => 'KZT',
                'status' => 'failed',
                'payment_method' => 'card',
                'payment_id' => 'PAY-' . $faker->unique()->randomNumber(8),
                'description' => 'Неудачная попытка оплаты',
                'ip_address' => $faker->ipv4,
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1)',
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id' => $users[array_rand($users)],
                'amount' => 100000,
                'currency' => 'KZT',
                'status' => 'refunded',
                'payment_method' => 'card',
                'payment_id' => 'PAY-' . $faker->unique()->randomNumber(8),
                'description' => 'Возврат средств по запросу клиента',
                'ip_address' => $faker->ipv4,
                'user_agent' => 'Mozilla/5.0 (Linux; Android 11)',
                'refund_status' => 'completed',
                'refund_reason' => 'Запрос клиента на возврат',
                'refunded_at' => now()->subHours(6),
                'created_at' => now()->subDays(3),
            ],
            [
                'user_id' => $users[array_rand($users)],
                'amount' => 75000,
                'currency' => 'KZT',
                'status' => 'completed',
                'payment_method' => 'crypto',
                'payment_id' => 'PAY-' . $faker->unique()->randomNumber(8),
                'description' => 'Оплата криптовалютой',
                'ip_address' => $faker->ipv4,
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                'created_at' => now()->subDays(4),
            ]
        ];

        // Добавляем еще 5 случайных платежей
        for ($i = 0; $i < 5; $i++) {
            $status = $statuses[array_rand($statuses)];
            $payment = [
                'user_id' => $users[array_rand($users)],
                'amount' => $faker->numberBetween(1000, 200000),
                'currency' => $currencies[array_rand($currencies)],
                'status' => $status,
                'payment_method' => $methods[array_rand($methods)],
                'payment_id' => 'PAY-' . $faker->unique()->randomNumber(8),
                'description' => $faker->sentence,
                'ip_address' => $faker->ipv4,
                'user_agent' => $faker->userAgent,
                'created_at' => now()->subDays($faker->numberBetween(1, 30)),
            ];

            if ($status === 'refunded') {
                $payment['refund_status'] = 'completed';
                $payment['refund_reason'] = $faker->sentence;
                $payment['refunded_at'] = now()->subHours($faker->numberBetween(1, 72));
            }

            $payments[] = $payment;
        }

        foreach ($payments as $payment) {
            Payment::create($payment);
        }
    }
}
