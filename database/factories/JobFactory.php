<?php

namespace Database\Factories;

use App\Domain\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        return [
            'open_vacancies' => 2,
            'title' => fake()->jobTitle(),
            'from_date' => now(),
            'to_date' => now()->addMonth(),
            'job_description' => fake()->regexify('[A-Za-z0-9]{20}'),
            'company_description' => fake()->regexify('[A-Za-z0-9]{20}'),
        ];
    }
}
