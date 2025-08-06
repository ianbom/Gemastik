<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\ReportMedia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        Report::factory(50)
            ->has(ReportMedia::factory()->count(rand(1, 3)), 'media')
            ->create();
    }
}
