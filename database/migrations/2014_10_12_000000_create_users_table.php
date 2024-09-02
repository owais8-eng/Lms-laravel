<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\roles;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine ='InnoDB';

            $table->bigIncrements('id');
            $table->string('uid')->nullable();
            $table->string('fcm_token')->nullable(); 
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birthdate');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('gender',['male','female']);
            $table->enum('role',['student','teacher','admin']);
            $table->string('phone')->unique();
            $table->string('address');
            $table->string('profile_picture_path')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
