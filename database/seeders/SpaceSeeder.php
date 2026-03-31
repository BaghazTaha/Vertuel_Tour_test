<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Space;
use Illuminate\Database\Seeder;

class SpaceSeeder extends Seeder
{
    public function run(): void
    {
        $it = Department::where('name', 'Information Technology')->first();

        Space::firstOrCreate(
            ['name' => 'Main Office Lobby'],
            [
                'department_id'  => null,
                'description'    => 'The main entrance and welcome area of the company.',
                'photo_360_path' => null,
                'thumbnail_path' => null,
            ]
        );

        Space::firstOrCreate(
            ['name' => 'IT Department Floor'],
            [
                'department_id'  => $it?->id,
                'description'    => 'Open-space floor hosting the IT and development teams.',
                'photo_360_path' => null,
                'thumbnail_path' => null,
            ]
        );
    }
}