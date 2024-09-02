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
        Schema::create('tests', function (Blueprint $table) {
            $table->engine ='InnoDB';

            $table->bigIncrements('id');

            $table->unsignedBigInteger('class_subject_id');
            $table->foreign('class_subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
            
            $table->enum('type',['exam','oral_exam','homework','quiz']);
            $table->string('exam_paper_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
