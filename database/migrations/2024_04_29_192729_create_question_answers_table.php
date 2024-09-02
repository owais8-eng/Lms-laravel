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
        Schema::create('question_answers', function (Blueprint $table) {
            $table->engine ='InnoDB';

            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('task_question_id')->nullable();
            $table->foreign('task_question_id')->references('id')->on('task_questions')->onDelete('cascade');

            $table->string('the_answer');
            $table->boolean('correct_answer')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_answers');
    }
};
