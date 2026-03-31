<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Human Resources',    'description' => 'Manages recruitment, onboarding and HR policies.'],
            ['name' => 'Information Technology', 'description' => 'Handles all IT infrastructure and software systems.'],
            ['name' => 'Finance',            'description' => 'Manages budgets, accounting and financial reporting.'],
            ['name' => 'Marketing',          'description' => 'Oversees brand, campaigns and communications.'],
            ['name' => 'Operations',         'description' => 'Coordinates day-to-day operational activities.'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept['name']], $dept);
        }
    }
}