<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@learninghub.test'],
            [
                'name' => 'LearningHub Admin',
                'password' => 'password',
            ]
        );
    }
}
