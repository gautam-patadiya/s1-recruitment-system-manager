<?php

namespace Database\Seeders;

use App\Domain\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        DocumentType::truncate();

        DocumentType::create([
            'name' => 'CV',
        ]);
    }
}
