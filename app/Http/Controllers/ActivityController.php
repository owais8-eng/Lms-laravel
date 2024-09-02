<?php

namespace App\Http\Controllers;

use App\Models\student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class ActivityController extends Controller
{
    public function activity(){
        $user_id = Auth::id();

        $student_id=DB::table('students')
        ->where('students.user_id',$user_id)
        ->value('students.id');

        $student = Student::find($student_id);

        if (!$student) {

            return response()->json('You are not student', 403);
        }


        $total_grades=DB::table('subjects')
        ->join('class_subjects','class_subjects.subject_id','subjects.id')
        ->join('classses','classses.id','class_subjects.class_id')
        ->where('classses.id',$student->class_id)
        ->select('subjects.total_grade')
        ->get();

        $total_homework=0;

        $total_exam=0;

        $total_oral_exam=0;

        $total_quiz=0;

        foreach($total_grades as $total_grade){

            $grade=$total_grade->total_grade;
            $total_homework+=0.2 * $grade;

            $total_exam+=0.4 * $grade;

            $total_oral_exam+=0.2 * $grade;

            $total_quiz+=0.2 * $grade;
        }

        $count=count($total_grades);

        $total_homework=$total_homework / $count;
        $total_exam=$total_exam / $count;
        $total_oral_exam=$total_oral_exam / $count;
        $total_quiz=$total_quiz / $count;

        $total_task=DB::table('tasks')
        ->join('class_subjects','class_subjects.id','tasks.class_subject_id')
        ->where('class_subjects.class_id',$student->class_id)
        ->avg('tasks.total_grade');


        $homework=DB::table('grades')
        ->where('grades.student_id',$student_id)
        ->join('tests','tests.id','grades.test_id')
        ->where('tests.type','homework')
        ->avg('grades.grade');

        if(!$homework){
            $homework=0;
        }

        $exam=DB::table('grades')
        ->where('grades.student_id',$student_id)
        ->join('tests','tests.id','grades.test_id')
        ->where('tests.type','exam')
        ->avg('grades.grade');

        if(!$exam){
            $exam=0;
        }

        $oral_exam=DB::table('grades')
        ->where('grades.student_id',$student_id)
        ->join('tests','tests.id','grades.test_id')
        ->where('tests.type','oral_exam')
        ->avg('grades.grade');

        if(!$oral_exam){
            $oral_exam=0;
        }
        
        $quiz=DB::table('grades')
        ->where('grades.student_id',$student_id)
        ->join('tests','tests.id','grades.test_id')
        ->where('tests.type','quiz')
        ->avg('grades.grade');

        if(!$quiz){
            $quiz=0;
        }
        
        $task=DB::table('task_grades')
        ->where('task_grades.student_id',$student_id)
        ->avg('task_grades.grade');

        if(!$task){
            $task=0;
        }

        $homework_percentage= 18 * $homework /$total_homework;
        $quiz_percentage= 18 * $quiz /$total_quiz;
        $exam_percentage= 18 * $exam /$total_exam;
        $oral_exam_percentage= 18 * $oral_exam /$total_oral_exam;
        $task_percentage= 28 * $task /$total_task;

 
        return response([
            'quiz'=>$quiz,
            'total_quiz'=>$total_quiz,
            'quiz_percentage'=>$quiz_percentage,
            
            'exam'=>$exam,
            'total_exam'=>$total_exam,
            'exam_percentage'=>$exam_percentage,
            
            'oral_exam'=>$oral_exam,
            'total_oral_exam'=>$total_oral_exam,
            'oral_exam_percentage'=>$oral_exam_percentage,
            
            'homework'=>$homework,
            'total_homework'=>$total_homework,
            'homewrk_percantage'=>$homework_percentage,
            
            'task'=>$task,
            'total_task'=>(int)$total_task,
            'task_percentage'=>$task_percentage

            
        ]);
    }
}
