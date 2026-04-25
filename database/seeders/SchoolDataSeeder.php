<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Trainer;
use App\Models\Group;
use App\Models\Student;
use App\Models\Space;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class SchoolDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trainerRole = Role::firstOrCreate(['name' => 'trainer']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $adminUserId = User::role('admin')->first()->id ?? 1;

        $department = Department::firstOrCreate(['name' => 'IT & Software Engineering']);
        $spaces = Space::take(3)->get();
        if ($spaces->count() == 0) {
            $spaces = collect([Space::create(['name' => 'Salle A1', 'type' => 'Classroom', 'capacity' => 30])]);
        }

        // 1. Create Trainers
        $trainersData = [
            ['first_name' => 'Ahmed', 'last_name' => 'Mansouri', 'specialty' => 'Développement Web'],
            ['first_name' => 'Sara', 'last_name' => 'Benali', 'specialty' => 'Bases de Données'],
        ];

        $trainers = [];
        foreach ($trainersData as $td) {
            $email = strtolower($td['first_name'] . '.' . $td['last_name'] . '@ecole.com');
            
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $td['first_name'] . ' ' . $td['last_name'],
                    'password' => Hash::make('password'),
                    'sex' => 'male',
                ]
            );
            $user->assignRole($trainerRole);

            $trainers[] = Trainer::firstOrCreate(
                ['email' => $email],
                [
                    'user_id' => $user->id,
                    'first_name' => $td['first_name'],
                    'last_name' => $td['last_name'],
                    'specialty' => $td['specialty'],
                    'department_id' => $department->id,
                    'phone' => '0600000000',
                ]
            );
        }

        // 2. Create Groups
        $groups = [];
        $groupNames = ['DEV101', 'DEV202'];
        foreach ($groupNames as $gName) {
            $groups[] = Group::firstOrCreate(
                ['name' => $gName],
                [
                    'max_capacity' => 25,
                    'description' => 'Groupe de développement informatique'
                ]
            );
        }

        // 3. Create Students
        $students = [];
        $faker = \Faker\Factory::create('fr_FR');
        foreach ($groups as $group) {
            for ($i = 0; $i < 10; $i++) {
                $firstName = $faker->firstName;
                $lastName = $faker->lastName;
                $email = strtolower($firstName . '.' . $lastName . rand(1,99) . '@student.com');

                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $firstName . ' ' . $lastName,
                        'password' => Hash::make('password'),
                        'sex' => rand(0,1) ? 'male' : 'female',
                    ]
                );
                $user->assignRole($studentRole);

                $students[] = Student::firstOrCreate(
                    ['email' => $email],
                    [
                        'user_id' => $user->id,
                        'group_id' => $group->id,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'phone' => $faker->phoneNumber,
                    ]
                );
            }
        }

        // 4. Create Schedules
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
        $schedules = [];
        foreach ($groups as $idx => $group) {
            $trainer = $trainers[$idx % count($trainers)];
            $space = $spaces[$idx % count($spaces)];
            
            $schedules[] = Schedule::firstOrCreate(
                [
                    'group_id' => $group->id,
                    'day_of_week' => $days[$idx % count($days)],
                    'start_time' => '08:30:00'
                ],
                [
                    'trainer_id' => $trainer->id,
                    'space_id' => $space->id,
                    'end_time' => '10:30:00',
                    'subject' => $trainer->specialty,
                ]
            );

            $schedules[] = Schedule::firstOrCreate(
                [
                    'group_id' => $group->id,
                    'day_of_week' => $days[($idx + 1) % count($days)],
                    'start_time' => '14:30:00'
                ],
                [
                    'trainer_id' => $trainers[($idx + 1) % count($trainers)]->id,
                    'space_id' => $spaces[($idx + 1) % count($spaces)]->id,
                    'end_time' => '16:30:00',
                    'subject' => $trainers[($idx + 1) % count($trainers)]->specialty,
                ]
            );
        }

        // 5. Create Attendances
        $statuses = ['present', 'present', 'present', 'present', 'absent', 'late'];
        
        foreach ($schedules as $schedule) {
            // Generate attendances for the last 3 weeks for this schedule
            for ($week = 1; $week <= 3; $week++) {
                $date = Carbon::now()->subWeeks($week)->startOfWeek();
                // Advance to the day of week
                $dateMap = ['Lundi'=>0, 'Mardi'=>1, 'Mercredi'=>2, 'Jeudi'=>3, 'Vendredi'=>4, 'Samedi'=>5, 'Dimanche'=>6];
                if (isset($dateMap[$schedule->day_of_week])) {
                    $date->addDays($dateMap[$schedule->day_of_week]);
                }

                $dateStr = $date->format('Y-m-d');
                $isValidated = ($week > 1); // 2 weeks ago validated, 1 week ago pending

                $groupStudents = Student::where('group_id', $schedule->group_id)->get();
                foreach ($groupStudents as $student) {
                    $status = $statuses[array_rand($statuses)];
                    $justification = ($status == 'late') ? 'Problème de transport' : '';
                    
                    Attendance::updateOrCreate(
                        [
                            'schedule_id' => $schedule->id,
                            'student_id' => $student->id,
                            'date' => $dateStr
                        ],
                        [
                            'status' => $status,
                            'justification' => $justification,
                            'is_validated' => $isValidated,
                            'validated_at' => $isValidated ? Carbon::now()->subDays(2) : null,
                            'validated_by' => $isValidated ? $adminUserId : null,
                        ]
                    );
                }
            }
        }
    }
}
