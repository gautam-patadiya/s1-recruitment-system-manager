<?php

use App\Domain\Models\Company;
use App\Domain\Models\Job;
use App\Domain\Models\JobDepartment;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Job::truncate();
        \DB::table('job_experience_levels')->truncate();
        \DB::table('job_qualifications')->truncate();

        $i = 1;
        do {
            $entity = factory(Job::class)->create();
            $entity->job_department_id = JobDepartment::query()->inRandomOrder()->first()->id;
            $entity->company_id = Company::query()->inRandomOrder()->first()->id;
            $entity->status = ($i > 7);
            $entity->save();
            $i++;
        } while($i <= 15);
    }
}
