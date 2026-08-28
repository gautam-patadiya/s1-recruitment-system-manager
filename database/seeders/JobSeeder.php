<?php

namespace Database\Seeders;

use App\Domain\Models\Company;
use App\Domain\Models\Job;
use App\Domain\Models\JobDepartment;
use Database\Factories\JobFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        Job::truncate();
        DB::table('job_experience_levels')->truncate();
        DB::table('job_qualifications')->truncate();

        for ($i = 1; $i <= 15; $i++) {
            $entity = JobFactory::new()->create();
            $entity->job_department_id = JobDepartment::query()->inRandomOrder()->first()->id;
            $entity->company_id = Company::query()->inRandomOrder()->first()->id;
            $entity->status = ($i > 7);
            $entity->save();
        }
    }
}
