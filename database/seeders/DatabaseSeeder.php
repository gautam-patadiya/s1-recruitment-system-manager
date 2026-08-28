<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
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
