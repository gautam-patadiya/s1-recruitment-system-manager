<?php

namespace Database\Seeders;

use App\Domain\Models\InterviewStage;
use Illuminate\Database\Seeder;

class InterviewStageSeeder extends Seeder
{
    public function run(): void
    {
        InterviewStage::truncate();

        InterviewStage::create([
            'title' => 'Telephonic',
            'i_order' => 1,
        ]);

        InterviewStage::create([
            'title' => 'Technical',
            'i_order' => 2,
        ]);

        InterviewStage::create([
            'title' => 'HR',
            'i_order' => 3,
        ]);
    }
}
