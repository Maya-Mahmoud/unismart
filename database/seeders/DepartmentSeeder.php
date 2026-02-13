<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'communications',
            'energy',
            'marine',
            'design_and_production',
            'computers',
            'medical',
            'mechatronics',
            'power',
        ];

        foreach ($departments as $department) {
            DB::table('departments')->updateOrInsert(['name' => $department]);
        }
    }
}
