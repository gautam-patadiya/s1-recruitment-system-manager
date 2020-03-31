<?php

use App\Domain\Models\Qualification;
use Illuminate\Database\Seeder;

class QualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Qualification::truncate();

        Qualification::create([
            'title' => 'Computer Science'
        ]);

        Qualification::create([
            'title' => 'MCA'
        ]);

        Qualification::create([
            'title' => 'BE'
        ]);

        Qualification::create([
            'title' => 'ME'
        ]);

        Qualification::create([
            'title' => 'MCOM'
        ]);

        Qualification::create([
            'title' => 'BCOM'
        ]);

        Qualification::create([
            'title' => 'MBBS'
        ]);
    }
}
