<?php

namespace Database\Seeders;

use App\Domain\Models\Qualification;
use Illuminate\Database\Seeder;

class QualificationSeeder extends Seeder
{
    public function run(): void
    {
        Qualification::truncate();

        foreach ([
            'Computer Science',
            'MCA',
            'BE',
            'ME',
            'MCOM',
            'BCOM',
            'MBBS',
        ] as $title) {
            Qualification::create([
                'title' => $title,
            ]);
        }
    }
}
