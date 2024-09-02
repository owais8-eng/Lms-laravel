<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use App\Models\Classs;
use App\Models\Subject;


class Class_subject extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classSubjects = [
            [
                'teacher_id' => 1,
                'class_id' => 1,
                'subject_id' =>1,
                'time_on_sun' => '1',
                'time_on_mon' => '2',
                'time_on_tue' => null,
                'time_on_wed' =>null,
                'time_on_thu' =>null,
                'exam_date_and_time' => now()->addDays(30)->setHour(14)->setMinute(0)->setSecond(0),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'teacher_id' =>2,
                'class_id' => 1,
                'subject_id' =>2 ,
               'time_on_sun' => '2',
                'time_on_mon' => '1',
                'time_on_tue' => null,
                'time_on_wed' => null,
                'time_on_thu' => null,
                'exam_date_and_time' => now()->addDays(45)->setHour(15)->setMinute(30)->setSecond(0),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'teacher_id' => 1,
                'class_id' => 2,
                'subject_id' =>1,
                'time_on_sun' =>null,
                'time_on_mon' =>null,
                'time_on_tue' => '1',
                'time_on_wed' => '2',
                'time_on_thu' => null,
                'exam_date_and_time' => now()->addDays(30)->setHour(14)->setMinute(0)->setSecond(0),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'teacher_id' =>2,
                'class_id' => 2,
                'subject_id' =>2 ,
                'time_on_sun' =>null,
                'time_on_mon' =>null,
                'time_on_tue' => '2',
                'time_on_wed' => '1',
                'time_on_thu' => null,
                'exam_date_and_time' => now()->addDays(45)->setHour(15)->setMinute(30)->setSecond(0),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'teacher_id' => 3,
                'class_id' => 3,
                'subject_id' =>3,
                'time_on_sun' => null,
                'time_on_mon' => null,
                'time_on_tue' => null,
                'time_on_wed' => null,
                'time_on_thu' => '1',
                'exam_date_and_time' => now()->addDays(30)->setHour(14)->setMinute(0)->setSecond(0),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'teacher_id' =>4,
                'class_id' => 3,
                'subject_id' =>4 ,
               'time_on_sun' => null,
                'time_on_mon' => null,
                'time_on_tue' => '5',
                'time_on_wed' => null,
                'time_on_thu' => '2',
                'exam_date_and_time' => now()->addDays(45)->setHour(15)->setMinute(30)->setSecond(0),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'teacher_id' => 3,
                'class_id' => 4,
                'subject_id' =>3,
               'time_on_sun' => '3',
                'time_on_mon' => '4',
                'time_on_tue' => null,
                'time_on_wed' => null,
                'time_on_thu' => null,
                'exam_date_and_time' => now()->addDays(30)->setHour(14)->setMinute(0)->setSecond(0),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'teacher_id' =>4,
                'class_id' => 4,
                'subject_id' =>4 ,
               'time_on_sun' => '4',
                'time_on_mon' => '3',
                'time_on_tue' => null,
                'time_on_wed' =>null,
                'time_on_thu' => null,
                'exam_date_and_time' => now()->addDays(45)->setHour(15)->setMinute(30)->setSecond(0),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'teacher_id' => 5,
                'class_id' => 5,
                'subject_id' =>5,
                'time_on_sun' => null,
                'time_on_mon' => null,
                'time_on_tue' => '3',
                'time_on_wed' => '4',
                'time_on_thu' => null,
                'exam_date_and_time' => now()->addDays(30)->setHour(14)->setMinute(0)->setSecond(0),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'teacher_id' =>6,
                'class_id' => 5,
                'subject_id' =>6 ,
                'time_on_sun' => null,
                'time_on_mon' => null,
                'time_on_tue' => '4',
                'time_on_wed' => '3',
                'time_on_thu' => null,
                'exam_date_and_time' => now()->addDays(45)->setHour(15)->setMinute(30)->setSecond(0),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'teacher_id' => 5,
                'class_id' => 6,
                'subject_id' =>5,
               'time_on_sun' => '6',
                'time_on_mon' => null,
                'time_on_tue' => null,
                'time_on_wed' => null,
                'time_on_thu' => '3',
                'exam_date_and_time' => now()->addDays(30)->setHour(14)->setMinute(0)->setSecond(0),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'teacher_id' =>6,
                'class_id' => 6,
                'subject_id' =>6 ,
                'time_on_sun' => '5',
                'time_on_mon' => null,
                'time_on_tue' => null,
                'time_on_wed' =>null,
                'time_on_thu' => '4',
                'exam_date_and_time' => now()->addDays(45)->setHour(15)->setMinute(30)->setSecond(0),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Add more class_subjects as needed
        ];

        // Insert class_subjects into 'class_subjects' table
        DB::table('class_subjects')->insert($classSubjects);

    }
}
