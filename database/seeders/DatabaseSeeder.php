<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            Classs::class,]);

        $this->call([
            CreateAdminSeeder::class,]);   
        $this->call([
            Subject::class,]);
        $this->call([
            Subject_units::class,]); 
        $this->call([
            StudentSeeder::class,]); 
        $this->call([
            TeacherSeeder::class,]); 
        $this->call([
            Class_subject::class,]);        
        $this->call([
            TaskSeeder::class,]); 
              
    }
}
