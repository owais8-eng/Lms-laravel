<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\teacher;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Joo',
                'last_name' => 'Dooo',
                'birthdate' => '1990-01-01',
                'email' => 'joo@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'male',
                'role' => 'teacher',
                'phone' => '1233',
                'address' => '123 Street, City',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'lili',
                'last_name' => 'mimi',
                'birthdate' => '1990-01-01',
                'email' => 'lili@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'female',
                'role' => 'teacher',
                'phone' => '12334',
                'address' => '123 Street, City',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'loo',
                'last_name' => 'goooo',
                'birthdate' => '1995-02-15',
                'email' => 'loo@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'female',
                'role' => 'teacher',
                'phone' => '9887',
                'address' => '456 Avenue, Town',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'hh',
                'last_name' => 'll',
                'birthdate' => '1995-02-15',
                'email' => 'hh@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'female',
                'role' => 'teacher',
                'phone' => '988709',
                'address' => '456 Avenue, Town',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'koo',
                'last_name' => 'pooo',
                'birthdate' => '1995-02-15',
                'email' => 'koo@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'female',
                'role' => 'teacher',
                'phone' => '9877',
                'address' => '456 Avenue, Town',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'opop',
                'last_name' => 'llol',
                'birthdate' => '1995-02-15',
                'email' => 'opop@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'female',
                'role' => 'teacher',
                'phone' => '9877889',
                'address' => '456 Avenue, Town',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more users as needed
        ];

        // Insert users into 'users' table
        DB::table('users')->insert($users);

        // Seed teachers
        $teachers = [
            [
                'user_id' => User::where('email', 'joo@example.com')->first()->id,
                'rate' => 5,
                'hire_date' => now()->subDays(30),  // Example hire date, adjust as needed
                'specialization' => 'Mathematics',
                'education' => 'Bachelor of Education',
                'salary' => 50000,
                'about' => 'Experienced mathematics teacher with a passion for teaching.',
                'class_level'=>'7',
                'cv'=>"/storage/images/QsgvL2aN1uMad2rtbLemXJiDWYZtIshvonFBxCo7.pdf",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => User::where('email', 'lili@example.com')->first()->id,
                'rate' => 3,
                'hire_date' => now()->subDays(30),  // Example hire date, adjust as needed
                'specialization' => 'Science',
                'education' => 'Bachelor of Education',
                'salary' => 30000,
                'about' => 'Experienced Science teacher with a passion for teaching.',
                'class_level'=>'7',
                'cv'=>"/storage/images/QsgvL2aN1uMad2rtbLemXJiDWYZtIshvonFBxCo7.pdf",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => User::where('email', 'loo@example.com')->first()->id,
                'rate' => 4.5,
                'hire_date' => now()->subDays(45),  // Example hire date, adjust as needed
                'specialization' => 'Science',
                'education' => 'Master of Science',
                'salary' => 60000,
                'about' => 'Science educator dedicated to fostering curiosity and critical thinking.',
                'class_level'=>'8',
                'cv'=>"/storage/images/QsgvL2aN1uMad2rtbLemXJiDWYZtIshvonFBxCo7.pdf",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => User::where('email', 'hh@example.com')->first()->id,
                'rate' => 4,
                'hire_date' => now()->subDays(45),  // Example hire date, adjust as needed
                'specialization' => 'Mathematics',
                'education' => 'Master of Mathematics',
                'salary' => 50000,
                'about' => 'Mathematics educator dedicated to fostering curiosity and critical thinking.',
                'class_level'=>'8',
                'cv'=>"/storage/images/QsgvL2aN1uMad2rtbLemXJiDWYZtIshvonFBxCo7.pdf",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => User::where('email', 'koo@example.com')->first()->id,
                'rate' => 5,
                'hire_date' => now()->subDays(30),  // Example hire date, adjust as needed
                'specialization' => 'Mathematics',
                'education' => 'Bachelor of Education',
                'salary' => 50000,
                'about' => 'Experienced mathematics teacher with a passion for teaching.',
                'class_level'=>'9',
                'cv'=>"/storage/images/QsgvL2aN1uMad2rtbLemXJiDWYZtIshvonFBxCo7.pdf",
                'created_at' => now(),
                'updated_at' => now(),
            ],   
            
            [
                'user_id' => User::where('email', 'opop@example.com')->first()->id,
                'rate' => 2.5,
                'hire_date' => now()->subDays(30),  // Example hire date, adjust as needed
                'specialization' => 'Science',
                'education' => 'Bachelor of Education',
                'salary' => 50000,
                'about' => 'Experienced Science teacher with a passion for teaching.',
                'class_level'=>'9',
                'cv'=>"/storage/images/QsgvL2aN1uMad2rtbLemXJiDWYZtIshvonFBxCo7.pdf",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more teachers as needed
        ];

        // Insert teachers into 'teachers' table
        DB::table('teachers')->insert($teachers);
    }
}
