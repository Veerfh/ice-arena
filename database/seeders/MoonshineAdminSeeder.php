<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use MoonShine\Models\MoonshineUser;
use MoonShine\Models\MoonshineUserRole;

class MoonshineAdminSeeder extends Seeder
{
    public function run()
    {
        // Создаем администратора
        $admin = MoonshineUser::firstOrCreate(
            ['email' => 'admin@mail.ru'],
            [
                'name' => 'Admin',
                'password' => bcrypt('123456'), 
            ]
        );
    }
}