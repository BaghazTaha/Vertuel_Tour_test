<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'first_name' => 'Youssef',
                'last_name'  => 'El Amrani',
                'matricule'  => 'EMP-001',
                'job_title'  => 'HR Manager',
                'department' => 'Human Resources',
                'email'      => 'youssef.elamrani@company.com',
                'phone'      => '+212 600-000001',
            ],
            [
                'first_name' => 'Salma',
                'last_name'  => 'Benali',
                'matricule'  => 'EMP-002',
                'job_title'  => 'Full Stack Developer',
                'department' => 'Information Technology',
                'email'      => 'salma.benali@company.com',
                'phone'      => '+212 600-000002',
            ],
            [
                'first_name' => 'Karim',
                'last_name'  => 'Idrissi',
                'matricule'  => 'EMP-003',
                'job_title'  => 'Financial Analyst',
                'department' => 'Finance',
                'email'      => 'karim.idrissi@company.com',
                'phone'      => '+212 600-000003',
            ],
            [
                'first_name' => 'Nadia',
                'last_name'  => 'Cherkaoui',
                'matricule'  => 'EMP-004',
                'job_title'  => 'Marketing Lead',
                'department' => 'Marketing',
                'email'      => 'nadia.cherkaoui@company.com',
                'phone'      => '+212 600-000004',
            ],
            [
                'first_name' => 'Omar',
                'last_name'  => 'Tazi',
                'matricule'  => 'EMP-005',
                'job_title'  => 'Operations Coordinator',
                'department' => 'Operations',
                'email'      => 'omar.tazi@company.com',
                'phone'      => '+212 600-000005',
            ],
        ];

        foreach ($employees as $data) {
            $dept = Department::where('name', $data['department'])->first();

            // Create a linked user account for each employee
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['first_name'] . ' ' . $data['last_name'],
                    'password' => Hash::make('password'),
                    'role'     => 'employee',
                ]
            );
            $user->assignRole('employee');

            Employee::firstOrCreate(
                ['matricule' => $data['matricule']],
                [
                    'user_id'       => $user->id,
                    'department_id' => $dept?->id,
                    'first_name'    => $data['first_name'],
                    'last_name'     => $data['last_name'],
                    'job_title'     => $data['job_title'],
                    'email'         => $data['email'],
                    'phone'         => $data['phone'],
                ]
            );
        }
    }
}