<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'birthdate' => '1990-01-01',
                'email' => 'john.doe@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'male',
                'role' => 'student',
                'phone' => '123456789',
                'address' => '123 Street, City',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'birthdate' => '1995-02-15',
                'email' => 'jane.smith@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'female',
                'role' => 'student',
                'phone' => '987654321',
                'address' => '456 Avenue, Town',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Johnson',
                'birthdate' => '1988-07-10',
                'email' => 'michael.johnson@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'male',
                'role' => 'student',
                'phone' => '000000',
                'address' => '789 Boulevard, Metropolis',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Brown',
                'birthdate' => '1992-04-25',
                'email' => 'emily.brown@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'female',
                'role' => 'student',
                'phone' => '111111111',
                'address' => '101 Main Street, Smalltown',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Clark',
                'birthdate' => '1993-09-18',
                'email' => 'david.clark@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'male',
                'role' => 'student',
                'phone' => '222222222',
                'address' => '456 Oak Avenue, Countryside',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Taylor',
                'birthdate' => '1994-11-30',
                'email' => 'sarah.taylor@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'female',
                'role' => 'student',
                'phone' => '333333333',
                'address' => '789 Maple Lane, Riverside',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Wilson',
                'birthdate' => '1991-06-20',
                'email' => 'james.wilson@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'male',
                'role' => 'student',
                'phone' => '444444444',
                'address' => '555 Cedar Street, Mountainside',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Sophia',
                'last_name' => 'Martinez',
                'birthdate' => '1993-03-12',
                'email' => 'sophia.martinez@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'female',
                'role' => 'student',
                'phone' => '555555555',
                'address' => '777 Pine Street, Forestville',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Daniel',
                'last_name' => 'Anderson',
                'birthdate' => '1990-08-05',
                'email' => 'daniel.anderson@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'gender' => 'male',
                'role' => 'student',
                'phone' => '666666666',
                'address' => '888 Elm Street, Suburbia',
                "profile_picture_path"=> "/storage/images/default_female_picture.jpg",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert users into 'users' table
        DB::table('users')->insert($users);

        // Seed students
        $students = [
            [
                'user_id' => User::where('email', 'john.doe@example.com')->first()->id,
                'class_id' => 1, // Replace with actual class_id from 'classes' table
                'wallet_balance' => 0,
                'enrollment_date' => now()->subDays(30),  // Example enrollment date, adjust as needed
                'parent_name' => 'John Doe Sr.',
                'parent_phone' => '1234567890',
                'parent_email' => 'parent1@example.com',
                'bus'=>'0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => User::where('email', 'jane.smith@example.com')->first()->id,
                'class_id' => 1, // Replace with actual class_id from 'classes' table
                'wallet_balance' => 0,
                'enrollment_date' => now()->subDays(45),  // Example enrollment date, adjust as needed
                'parent_name' => 'Jane Smith Sr.',
                'parent_phone' => '777777',
                'parent_email' => 'parent2@example.com',
                'bus'=>'0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => User::where('email', 'michael.johnson@example.com')->first()->id,
                'class_id' => 2, // Replace with actual class_id from 'classes' table
                'wallet_balance' => 0,
                'enrollment_date' => now()->subDays(30),  // Example enrollment date, adjust as needed
                'parent_name' => 'Michael Johnson Sr.',
                'parent_phone' => '88888888',
                'parent_email' => 'parent3@example.com',
                'bus'=>'0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => User::where('email', 'emily.brown@example.com')->first()->id,
                'class_id' => 3, // Replace with actual class_id from 'classes' table
                'wallet_balance' => 0,
                'enrollment_date' => now()->subDays(45),  // Example enrollment date, adjust as needed
                'parent_name' => 'Emily Brown Sr.',
                'parent_phone' => '99999999',
                'parent_email' => 'parent4@example.com',
                'bus'=>'1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => User::where('email', 'david.clark@example.com')->first()->id,
                'class_id' => 3, // Replace with actual class_id from 'classes' table
                'wallet_balance' => 0,
                'enrollment_date' => now()->subDays(30),  // Example enrollment date, adjust as needed
                'parent_name' => 'David Clark Sr.',
                'parent_phone' => '889988',
                'parent_email' => 'parent5@example.com',
                'bus'=>'1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => User::where('email', 'sarah.taylor@example.com')->first()->id,
                'class_id' => 4, // Replace with actual class_id from 'classes' table
                'wallet_balance' => 0,
                'enrollment_date' => now()->subDays(45),  // Example enrollment date, adjust as needed
                'parent_name' => 'Sarah Taylor Sr.',
                'parent_phone' => '33333339999',
                'parent_email' => 'parent6@example.com',
                'bus'=>'1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => User::where('email', 'james.wilson@example.com')->first()->id,
                'class_id' => 5, // Replace with actual class_id from 'classes' table
                'wallet_balance' => 0,
                'enrollment_date' => now()->subDays(30),  // Example enrollment date, adjust as needed
                'parent_name' => 'James Wilson Sr.',
                'parent_phone' => '444449999',
                'parent_email' => 'parent7@example.com',
                'bus'=>'1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => User::where('email', 'sophia.martinez@example.com')->first()->id,
                'class_id' => 5, // Replace with actual class_id from 'classes' table
                'wallet_balance' => 0,
                'enrollment_date' => now()->subDays(45),  // Example enrollment date, adjust as needed
                'parent_name' => 'Sophia Martinez Sr.',
                'parent_phone' => '555559999',
                'parent_email' => 'parent8@example.com',
                'bus'=>'1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => User::where('email', 'daniel.anderson@example.com')->first()->id,
                'class_id' => 6, // Replace with actual class_id from 'classes' table
                'wallet_balance' => 0,
                'enrollment_date' => now()->subDays(30),  // Example enrollment date, adjust as needed
                'parent_name' => 'Daniel Anderson Sr.',
                'parent_phone' => '666669999',
                'parent_email' => 'parent9@example.com',
                'bus'=>'1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert students into 'students' table
        DB::table('students')->insert($students);
    }
}