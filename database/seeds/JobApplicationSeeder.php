<?php

use App\Domain\Models\Job;
use App\Domain\Models\JobApplication;
use App\Domain\Models\User;
use Illuminate\Database\Seeder;

class JobApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JobApplication::truncate();

        $i = 1;
        do {
            $user = User::whereType(2)->inRandomOrder()->first();
            $job = Job::whereStatus(1)->inRandomOrder()->first();
            $jobApp = JobApplication::create([
                'job_id' => $job->id,
                'candidate_id' => $user->id,
                'status' => 1,
            ]);
            $jobApp->numberIncrement();

            $i++;
        } while ($i <= 5);
    }
}
