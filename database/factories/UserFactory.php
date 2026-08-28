<?php

namespace Database\Factories;

use App\Domain\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'gender' => 1,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'birthday' => fake()->date(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
