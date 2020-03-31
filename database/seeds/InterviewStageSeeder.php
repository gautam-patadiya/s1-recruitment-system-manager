<?php

use App\Domain\Models\InterviewStage;
use App\Domain\Models\JobDepartment;
use Illuminate\Database\Seeder;

class InterviewStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InterviewStage::truncate();

        InterviewStage::create([
            'title' => 'Telephonic',
            'i_order' => 1
        ]);

        InterviewStage::create([
            'title' => 'Technical',
            'i_order' => 2,
        ]);

        InterviewStage::create([
            'title' => 'HR',
            'i_order' => 3
        ]);
    }
}
