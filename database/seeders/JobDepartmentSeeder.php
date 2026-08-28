<?php

namespace Database\Seeders;

use App\Domain\Models\JobDepartment;
use Illuminate\Database\Seeder;

class JobDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        JobDepartment::truncate();

        foreach ([
            'Accounting',
            'Human resources (HR)',
            'Sales',
            'Purchase',
            'Marketing',
            'Public relations(PR)',
            'Management consulting',
            'Healthcare',
        ] as $name) {
            JobDepartment::create([
                'name' => $name,
            ]);
        }
    }
}
