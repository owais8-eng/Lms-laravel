<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class Subject_units extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subject_units')->insert([
            [
                'subject_id' => 1,
                'unit_number' => 1,
                'title' => 'Introduction to Algebra',
                'description' => 'Basic concepts of algebra including variables and equations.',
                "photo_path"=> "/storage/images/xARTOr06WbwFbSfQOrA7WbzhBlAh9bSsuPxSCqLa.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_id' => 1,
                'unit_number' => 2,
                'title' => 'Geometry Basics',
                'description' => 'Fundamentals of geometry including shapes and angles.',
                "photo_path"=> "/storage/images/xARTOr06WbwFbSfQOrA7WbzhBlAh9bSsuPxSCqLa.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_id' => 2,
                'unit_number' => 1,
                'title' => 'Introduction to Biology',
                'description' => 'Basic concepts of biology including cells and organisms.',
                "photo_path"=> "/storage/images/xARTOr06WbwFbSfQOrA7WbzhBlAh9bSsuPxSCqLa.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_id' => 2,
                'unit_number' => 2,
                'title' => 'Introduction to Physics',
                'description' => 'Fundamentals of physics including motion and forces.',
                "photo_path"=> "/storage/images/xARTOr06WbwFbSfQOrA7WbzhBlAh9bSsuPxSCqLa.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_id' => 3,
                'unit_number' => 1,
                'title' => 'Introduction to Algebra',
                'description' => 'Basic concepts of algebra including variables and equations.',
                "photo_path"=> "/storage/images/xARTOr06WbwFbSfQOrA7WbzhBlAh9bSsuPxSCqLa.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_id' => 3,
                'unit_number' => 2,
                'title' => 'Geometry Basics',
                'description' => 'Fundamentals of geometry including shapes and angles.',
                "photo_path"=> "/storage/images/xARTOr06WbwFbSfQOrA7WbzhBlAh9bSsuPxSCqLa.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_id' => 4,
                'unit_number' => 1,
                'title' => 'Introduction to Biology',
                'description' => 'Basic concepts of biology including cells and organisms.',
                "photo_path"=> "/storage/images/xARTOr06WbwFbSfQOrA7WbzhBlAh9bSsuPxSCqLa.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_id' => 4,
                'unit_number' => 2,
                'title' => 'Introduction to Physics',
                'description' => 'Fundamentals of physics including motion and forces.',
                "photo_path"=> "/storage/images/xARTOr06WbwFbSfQOrA7WbzhBlAh9bSsuPxSCqLa.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_id' => 5,
                'unit_number' => 1,
                'title' => 'Introduction to Algebra',
                'description' => 'Basic concepts of algebra including variables and equations.',
                "photo_path"=> "/storage/images/xARTOr06WbwFbSfQOrA7WbzhBlAh9bSsuPxSCqLa.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_id' => 5,
                'unit_number' => 2,
                'title' => 'Geometry Basics',
                'description' => 'Fundamentals of geometry including shapes and angles.',
                "photo_path"=> "/storage/images/xARTOr06WbwFbSfQOrA7WbzhBlAh9bSsuPxSCqLa.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_id' => 6,
                'unit_number' => 1,
                'title' => 'Introduction to Biology',
                'description' => 'Basic concepts of biology including cells and organisms.',
                "photo_path"=> "/storage/images/xARTOr06WbwFbSfQOrA7WbzhBlAh9bSsuPxSCqLa.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_id' => 6,
                'unit_number' => 2,
                'title' => 'Introduction to Physics',
                'description' => 'Fundamentals of physics including motion and forces.',
                "photo_path"=> "/storage/images/xARTOr06WbwFbSfQOrA7WbzhBlAh9bSsuPxSCqLa.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            

        ]);
    }
}
