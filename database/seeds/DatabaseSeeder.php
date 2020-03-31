<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UserSeeder::class);
         $this->call(ExperienceLevelSeeder::class);
         $this->call(QualificationSeeder::class);
         $this->call(JobDepartmentSeeder::class);
         $this->call(InterviewStageSeeder::class);
         $this->call(CompanySeeder::class);
         $this->call(JobSeeder::class);
         $this->call(JobApplicationSeeder::class);
         $this->call(DocumentTypeSeeder::class);
    }
}
