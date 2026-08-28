<?php

namespace Database\Seeders;

use App\Domain\Models\Company;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::truncate();

        for ($i = 1; $i <= 15; $i++) {
            CompanyFactory::new()->create();
        }
    }
}
