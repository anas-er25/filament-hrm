<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departements')->delete();
        DB::table('departements')->insert([
            ['name' => 'Human Resources'],
            ['name' => 'Finance'],
            ['name' => 'Marketing'],
            ['name' => 'Sales'],
            ['name' => 'IT'],
            ['name' => 'Customer Service'],
            ['name' => 'Research and Development'],
            ['name' => 'Logistics'],
            ['name' => 'Production'],
            ['name' => 'Legal']
        ]);
    }
}
