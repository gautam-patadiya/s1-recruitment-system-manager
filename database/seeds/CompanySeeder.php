<?php

use App\Domain\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i = 1;
        Company::truncate();

        do {
            factory(Company::class)->create();

            $i++;
        } while($i <= 15);
    }
}
