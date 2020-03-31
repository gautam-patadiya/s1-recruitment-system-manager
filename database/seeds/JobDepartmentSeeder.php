<?php

use App\Domain\Models\JobDepartment;
use Illuminate\Database\Seeder;

class JobDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JobDepartment::truncate();

        JobDepartment::create([
            'name' => 'Accounting'
        ]);

        JobDepartment::create([
            'name' => 'Human resources (HR)'
        ]);

        JobDepartment::create([
            'name' => 'Sales'
        ]);

        JobDepartment::create([
            'name' => 'Purchase'
        ]);

        JobDepartment::create([
            'name' => 'Marketing'
        ]);

        JobDepartment::create([
            'name' => 'Public relations(PR)'
        ]);

        JobDepartment::create([
            'name' => 'Management consulting'
        ]);

        JobDepartment::create([
            'name' => 'Healthcare'
        ]);
    }
}
