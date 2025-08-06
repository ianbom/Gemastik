<?php

namespace Database\Seeders;

use App\Models\Certificate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Certificate::create([
            'name' => 'template-1',
            'template' => 'default'
        ]);

        Certificate::create([
            'name' => 'template-2',
            'template' => 'new-template'
        ]);
    }
}
