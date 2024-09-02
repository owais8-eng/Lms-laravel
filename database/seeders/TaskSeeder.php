<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\task;
use App\Models\task_question;
use App\Models\question_answer;

class TaskSeeder extends Seeder
{
    public function run()
    {


        $task = Task::create([
            'class_subject_id' => '2',
            'total_grade'=>15
        ]);

        // Create task questions
        $questions = [
            [
                'the_question' => 'What is 2 + 2?',
                'question_grade' => 5,
            ],
            [
                'the_question' => 'What is the capital of France?',
                'question_grade' => 10,
            ],
        ];

        $task_questions = [];
        foreach ($questions as $question_data) {
            $task_questions[] = task_question::create([
                'task_id' => $task->id,
                'the_question' => $question_data['the_question'],
                'question_grade' => $question_data['question_grade'],
            ]);
        }

        // Create question answers
        $answers = [
            [
                'task_question_id' => $task_questions[0]->id,
                'the_answer' => '4',
                'correct_answer' => true,
            ],
            [
                'task_question_id' => $task_questions[0]->id,
                'the_answer' => '3',
                'correct_answer' => false,
            ],
            [
                'task_question_id' => $task_questions[1]->id,
                'the_answer' => 'Paris',
                'correct_answer' => true,
            ],
            [
                'task_question_id' => $task_questions[1]->id,
                'the_answer' => 'London',
                'correct_answer' => false,
            ],
        ];

        foreach ($answers as $answer_data) {
            question_answer::create($answer_data);
        }
        $task = Task::create([
            'class_subject_id' => 5,
            'total_grade'=> 30
        ]);

        // Create task questions
        $questions = [
            [
                'the_question' => 'What is 2 + 2?',
                'question_grade' => 5,
            ],
            [
                'the_question' => 'What is the capital of France?',
                'question_grade' => 10,
            ],
            [
                'the_question' => 'What is the largest planet in our solar system?',
                'question_grade' => 8,
            ],
            [
                'the_question' => 'What is the chemical symbol for water?',
                'question_grade' => 7,
            ],
        ];

        $task_questions = [];
        foreach ($questions as $question_data) {
            $task_questions[] = task_question::create([
                'task_id' => $task->id,
                'the_question' => $question_data['the_question'],
                'question_grade' => $question_data['question_grade'],
            ]);
        }

        // Create question answers
        $answers = [
            [
                'task_question_id' => $task_questions[0]->id,
                'the_answer' => '4',
                'correct_answer' => true,
            ],
            [
                'task_question_id' => $task_questions[0]->id,
                'the_answer' => '3',
                'correct_answer' => false,
            ],
            [
                'task_question_id' => $task_questions[1]->id,
                'the_answer' => 'Paris',
                'correct_answer' => true,
            ],
            [
                'task_question_id' => $task_questions[1]->id,
                'the_answer' => 'London',
                'correct_answer' => false,
            ],
            [
                'task_question_id' => $task_questions[2]->id,
                'the_answer' => 'Jupiter',
                'correct_answer' => true,
            ],
            [
                'task_question_id' => $task_questions[2]->id,
                'the_answer' => 'Mars',
                'correct_answer' => false,
            ],
            [
                'task_question_id' => $task_questions[3]->id,
                'the_answer' => 'H2O',
                'correct_answer' => true,
            ],
            [
                'task_question_id' => $task_questions[3]->id,
                'the_answer' => 'O2',
                'correct_answer' => false,
            ],
        ];

        foreach ($answers as $answer_data) {
            question_answer::create($answer_data);
        }

        
        $task = Task::create([
            'class_subject_id' => '1',
            'total_grade'=>15
        ]);

        // Create task questions
        $questions = [
            [
                'the_question' => 'What is 2 + 2?',
                'question_grade' => 5,
            ],
            [
                'the_question' => 'What is the capital of France?',
                'question_grade' => 10,
            ],
        ];

        $task_questions = [];
        foreach ($questions as $question_data) {
            $task_questions[] = task_question::create([
                'task_id' => $task->id,
                'the_question' => $question_data['the_question'],
                'question_grade' => $question_data['question_grade'],
            ]);
        }

        // Create question answers
        $answers = [
            [
                'task_question_id' => $task_questions[0]->id,
                'the_answer' => '4',
                'correct_answer' => true,
            ],
            [
                'task_question_id' => $task_questions[0]->id,
                'the_answer' => '3',
                'correct_answer' => false,
            ],
            [
                'task_question_id' => $task_questions[1]->id,
                'the_answer' => 'Paris',
                'correct_answer' => true,
            ],
            [
                'task_question_id' => $task_questions[1]->id,
                'the_answer' => 'London',
                'correct_answer' => false,
            ],
        ];

        foreach ($answers as $answer_data) {
            question_answer::create($answer_data);
        }
        
    }
    
}
