<?php

use App\Domain\Models\ExperienceLevel;
use Illuminate\Database\Seeder;

class ExperienceLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExperienceLevel::truncate();

        ExperienceLevel::create([
            'title' => 'Intermediate'
        ]);

        ExperienceLevel::create([
            'title' => 'Beginners'
        ]);

        ExperienceLevel::create([
            'title' => 'Professional'
        ]);
    }
}
