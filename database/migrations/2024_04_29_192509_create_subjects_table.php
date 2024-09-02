<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->engine ='InnoDB';

            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->enum('the_class',['7','8','9'] )->nullable();

            $table->text('about_subject')->nullable();
            $table->string('book_path')->nullable();
            $table->string('photo_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
