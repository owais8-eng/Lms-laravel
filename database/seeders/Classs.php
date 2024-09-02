<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class Classs extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('classses')->insert([
            [
                'class_level' => 7,
                'class_number' => 1,
            ],
            [
                'class_level' => 7,
                'class_number' => 2,
            ],
            [
                'class_level' => 8,
                'class_number' => 1,
            ],
            [
                'class_level' => 8,
                'class_number' => 2,
            ],
            [
                'class_level' => 9,
                'class_number' => 1,
            ],
            [
                'class_level' => 9,
                'class_number' => 2,
            ],
        ]);
    }
}
