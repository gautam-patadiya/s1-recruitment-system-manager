<?php

namespace Database\Seeders;

use App\Domain\Models\ExperienceLevel;
use Illuminate\Database\Seeder;

class ExperienceLevelSeeder extends Seeder
{
    public function run(): void
    {
        ExperienceLevel::truncate();

        ExperienceLevel::create([
            'title' => 'Intermediate',
        ]);

        ExperienceLevel::create([
            'title' => 'Beginners',
        ]);

        ExperienceLevel::create([
            'title' => 'Professional',
        ]);
    }
}
