<?php

namespace Database\Factories;

use App\Domain\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'additional_contact' => fake()->name(),
            'additional_contact_number' => fake()->phoneNumber(),
            'description' => fake()->jobTitle(),
            'address' => fake()->address(),
            'status' => 1,
        ];
    }
}
