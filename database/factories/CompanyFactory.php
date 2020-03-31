<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Models\Company;
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

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'email' => $faker->companyEmail,
        'phone' => $faker->phoneNumber,
        'additional_contact' => $faker->name,
        'additional_contact_number' => $faker->phoneNumber,
        'description' => $faker->jobTitle,
        'address' => $faker->address,
        'status' => 1,
    ];
});
