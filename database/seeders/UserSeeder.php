<?php

namespace Database\Seeders;

use App\Domain\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        UserFactory::new()->create([
            'email' => 'admin@admin.com',
            'gender' => 1,
            'type' => 1,
        ]);

        for ($i = 1; $i <= 15; $i++) {
            UserFactory::new()->create([
                'gender' => ($i < 5) ? 1 : 2,
                'type' => ($i < 5) ? 1 : 2,
            ]);
        }
    }
}
