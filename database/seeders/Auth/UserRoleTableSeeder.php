<?php

namespace Database\Seeders\Auth;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * Class UserRoleTableSeeder.
 */
class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        User::findOrFail(4)->assignRole('super admin');
        User::findOrFail(5)->assignRole('administrator');
        User::findOrFail(6)->assignRole('manager');
        User::findOrFail(7)->assignRole('executive');
        User::findOrFail(8)->assignRole('user');

        Artisan::call('cache:clear');
    }
}
