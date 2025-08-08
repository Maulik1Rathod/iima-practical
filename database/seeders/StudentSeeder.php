<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $departments = ['CS', 'ME', 'EE'];
        $subjects = [
            'CS' => 'CS101',
            'ME' => 'ME202',
            'EE' => 'EE305',
        ];

        $students = [];

        // 60 Male students
        for ($i = 1; $i <= 60; $i++) {
            $dept = $departments[array_rand($departments)];
            $students[] = [
                'name' => 'Student M' . $i,
                'roll_number' => 'M' . $i .rand(100,999),
                'gender' => 'M',
                'department' => $dept,
                'subject_code' => $subjects[$dept],
                'special_needs' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 40 Female students
        for ($i = 1; $i <= 40; $i++) {
            $dept = $departments[array_rand($departments)];
            $students[] = [
                'name' => 'Student F' . $i,
                'roll_number' => 'F' . $i .rand(100,999),
                'gender' => 'F',
                'department' => $dept,
                'subject_code' => $subjects[$dept],
                'special_needs' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Randomly assign special_needs = true to any 5 students
        $specialNeedsIndexes = array_rand($students, 5);
        foreach ((array)$specialNeedsIndexes as $idx) {
            $students[$idx]['special_needs'] = true;
        }

        Student::insert($students);
    }
}
