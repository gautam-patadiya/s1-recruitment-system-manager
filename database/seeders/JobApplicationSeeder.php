<?php

namespace Database\Seeders;

use App\Domain\Models\Job;
use App\Domain\Models\JobApplication;
use App\Domain\Models\User;
use Illuminate\Database\Seeder;

class JobApplicationSeeder extends Seeder
{
    public function run(): void
    {
        JobApplication::truncate();

        for ($i = 1; $i <= 5; $i++) {
            $user = User::whereType(2)->inRandomOrder()->first();
            $job = Job::whereStatus(1)->inRandomOrder()->first();

            $jobApp = JobApplication::create([
                'job_id' => $job->id,
                'candidate_id' => $user->id,
                'status' => 1,
            ]);

            $jobApp->numberIncrement();
        }
    }
}
