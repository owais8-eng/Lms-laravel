<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateAdminSeeder extends Seeder
{
//this is seeder for create admin
    public function run(): void
    {

        $user =  User::create([
            'role' => 'admin',
            'first_name' => 'owais',
            'last_name' => 'aboud',
            'email' => 'owaisaboud1@gmail.com',
            'password' => Hash::make('12345678'),

            'phone' => '07777777',
            'birthdate' => '2024-12-12',
            'address' => 'Daraa',
            'profile_picture_path' => 'storage\images\ITClZCt4oMrvwBeRqVkttDac8IEzUZ5JIcFrD0Cg.png',
            'gender' => 'male',

        ]);
        $user['admin'] = admin::create([
            'user_id' => $user->id,
        ]);
    }

}

