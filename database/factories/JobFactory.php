<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Models\Job;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Job::class, function (Faker $faker) {
    return [
        'open_vacancies' => 2,
        'title' => $faker->jobTitle,
        'from_date' => now(),
        'to_date' => now()->addMonth(),
        'job_description' => $faker->regexify('[A-Za-z0-9]{20}'),
        'company_description' => $faker->regexify('[A-Za-z0-9]{20}'),
    ];
});
