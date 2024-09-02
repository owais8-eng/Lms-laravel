<?php

namespace App\Http\Controllers;


use App\Models\task;
use App\Models\task_grade;
use App\Models\task_question;
use App\Models\question_answer;
use App\Models\user;
use App\Models\student;
use App\Models\class_subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\NotificationsTrait;


class TaskController extends Controller

{
    use NotificationsTrait;

    public function store_task(request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id'=>'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user_id = Auth::id();
        $teacher_id = DB::table('teachers')
            ->where('teachers.user_id', $user_id)
            ->value('teachers.id');

        if(!$teacher_id){

            return response()->json('you are not a techer',400);
        }    

        $class_subject_id = DB::table('class_subjects')
            ->where('class_subjects.teacher_id', $teacher_id)
            ->where('class_subjects.class_id',$request->class_id)
            ->value('class_subjects.id');

        if (!$class_subject_id) {
            return response()->json('try_again',400);
        }
        $task = task::create([
            'class_subject_id' => $class_subject_id,
            'finished'=>0,
            'total_grade' => 0

        ]);

        return response()->json($task,200);

    }
    public function lock_task(request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id'=>'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user_id = Auth::id();
        $teacher_id = DB::table('teachers')
            ->where('teachers.user_id', $user_id)
            ->value('teachers.id');

        if(!$teacher_id){

            return response()->json('you are not a techer',400);
        }    

       
        $task = task::find($request->task_id);

        if(!$task){
            return response()->json('task not found',400);
        }

        $task->finished=1;
        $task->save();

        return response()->json($task,200);

    }
    public function store_question(request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id'=>'required|integer',
            'the_question' => 'required|string',
            'question_grade' => 'required|integer',
            'answers' => 'required|array',
            'answers.*.the_answer' => 'required|string',
            'answers.*.correct_answer' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user_id = Auth::id();
        $teacher_id = DB::table('teachers')
            ->where('teachers.user_id', $user_id)
            ->value('teachers.id');

        if(!$teacher_id){
            return response()->json('you are not teacher',400);
        }    

        $class_subject_id = DB::table('class_subjects')
        ->where('class_subjects.teacher_id', $teacher_id)
        ->join('tasks','tasks.class_subject_id','class_subjects.id')
        ->value('class_subjects.id');

        if (!$class_subject_id) {
            return response()->json('try again',400);

        }

        $task=task::where('tasks.id',$request->task_id)->first();

        if (!$task) {
            return response()->json('this task not found',400);
        }


        if($task->finished ){
                return response()->json('this task is locked',400); 
        }
        $task_question = Task_question::create([
            'task_id' => $task->id,
            'the_question' => $request->the_question,
            'question_grade' => $request->question_grade
        ]);            
        $question_answers = [];

        foreach ($request->answers as $answer_data) {
            $question_answer = question_answer::create([
                'task_question_id' => $task_question->id,
                'the_answer' => $answer_data['the_answer'],
                'correct_answer' => $answer_data['correct_answer']
            ]);
            $question_answers[] = $question_answer;
        }

        
        $task_grade = $task->total_grade + $request->question_grade;
        $task->total_grade=$task_grade;
        $task->save();
        
        $tokens = User::
        join('students','students.user_id','users.id')
        ->where('students.class_id', $request->class_id)
        ->pluck('fcm_token')->toArray(); 
        
        $title = 'New Task';
        $body = 'A new task has been uploaded.';
        
        $this->sendNotification($title, $body,$tokens); 
    


        return response([
            'question'=>$task_question,
            'answers'=>$question_answers
        ], 200);
    }

    public function show_all_tasks_for_student()
    {

        $user_id = Auth::id();

        $student_id = DB::table('students')
            ->where('students.user_id', $user_id)
            ->value('students.id');

        $student = Student::find($student_id);

        if (!$student) {

            return response()->json('You are not student', 403);
        }

        $tasks = DB::table('tasks')
            ->where('tasks.finished','1')
            ->join('class_subjects', 'class_subjects.id', 'tasks.class_subject_id')
            ->join('classses', 'classses.id', 'class_subjects.class_id')
            ->where('classses.id', $student->class_id)
            ->join('subjects', 'subjects.id', 'class_subjects.subject_id')
            ->join('teachers', 'teachers.id', 'class_subjects.teacher_id')
            ->join('users', 'users.id', 'teachers.user_id')
            ->select('tasks.*', 'subjects.name', 'users.first_name', 'users.last_name')
            ->get();


        $solved_tasks = [];
        $unsolved_tasks = [];
        $grades = [];
        foreach ($tasks as $task) {

            $task_id = $task->id;
            
            $check = DB::table('task_grades')
            ->where('task_grades.task_id', $task_id)
            ->join('students', 'students.id', 'task_grades.student_id')
            ->where('students.id', $student_id)
            ->select('task_grades.grade','task_grades.task_id')
            ->get();

            $check1 = DB::table('task_grades')
                ->where('task_grades.task_id', $task_id)
                ->join('students', 'students.id', 'task_grades.student_id')
                ->where('students.id', $student_id)
                ->first('task_grades.grade');

            if ($check1) {
                $solved_tasks[$task_id] = $task;
                $grades[$task_id] = $check;
            } else {
                $unsolved_tasks[$task_id] = $task;
            }
        }


        return response([
            'solved' => array_values($solved_tasks),
            'grades'=>collect($grades)->flatten(1)->all(),  
            'unsolved' => array_values($unsolved_tasks)
        ]);
    }
    public function show_all_tasks_for_teacher()
    {

        $user_id = Auth::id();

        $teacher_id = DB::table('teachers')
            ->where('teachers.user_id', $user_id)
            ->value('teachers.id');

        $teacher = Student::find($teacher_id);

        if (!$teacher) {

            return response()->json('You are not teacher', 403);
        }


        $tasks = DB::table('tasks')
            ->join('class_subjects', 'class_subjects.id', 'tasks.class_subject_id')
            ->join('subjects', 'subjects.id', 'class_subjects.subject_id')
            ->join('teachers', 'teachers.id', 'class_subjects.teacher_id')
            ->where('teachers.id', $teacher_id)
            ->join('classses', 'classses.id', 'class_subjects.class_id')
            ->select('tasks.id','tasks.finished','tasks.total_grade', 'subjects.name as subject_name','classses.class_level','classses.class_number' )
            ->get();

        return response()->json(['tasks'=>$tasks], 200);
    }


    public function show_classes_for_teacher_for_joud(){

        $user_id = Auth::id();
        $teacher_id = DB::table('teachers')
            ->where('teachers.user_id', $user_id)
            ->value('teachers.id');

        if(!$teacher_id){

            return response()->json('You are not teacher', 403);
        }
        
        $class_subject = DB::table('class_subjects')
            ->where('class_subjects.teacher_id', $teacher_id)
            ->join('classses','classses.id','class_subjects.class_id')
            ->select('classses.class_level','classses.class_number','classses.id')
            ->orderBy('classses.class_level')
            ->orderBy('classses.class_number')
            ->get();

        if(count($class_subject)==0){

            return response()->json('there is no classes for you', 403);

        }    
            return response()->json(['classes'=>$class_subject],200);

    }

    public function show_task($id)
    {

        $user_id = Auth::id();

        $student_id = DB::table('students')
            ->where('students.user_id', $user_id)
            ->value('students.id');

        $student = Student::find($student_id);

        if (!$student) {

            return response()->json('You are not student', 403);
        }


        $task = task::where('tasks.finished','1')->find($id);

        if (!$task) {
            return response('there is no task');
        }

        $check1 = DB::table('tasks')
            ->where('tasks.id', $id)
            ->join('class_subjects', 'class_subjects.id', 'tasks.class_subject_id')
            ->join('classses', 'classses.id', 'class_subjects.class_id')
            ->where('classses.id', $student->class_id)
            ->select('tasks.*')
            ->first();

        if (!$check1) {
            return response()->json(['error' => 'this task is not available to you'], 404);
        }

        $check2 = DB::table('task_grades')
            ->where('task_grades.task_id', $id)
            ->join('students', 'students.id', 'task_grades.student_id')
            ->where('students.id', $student_id)
            ->select('task_grades.*')
            ->get();

        if (count($check2) !== 0) {
            return response()->json(['error' => 'you have already solved this task'], 404);
        }


        $the_task = DB::table('tasks')
            ->where('tasks.id', $id)
            ->join('class_subjects', 'class_subjects.id', 'tasks.class_subject_id')
            ->join('subjects', 'subjects.id', 'class_subjects.subject_id')
            ->join('teachers', 'teachers.id', 'class_subjects.teacher_id')
            ->join('users', 'users.id', 'teachers.user_id')
            ->select('tasks.*', 'subjects.name', 'users.first_name', 'users.last_name')
            ->get();

        $the_questions = DB::table('tasks')
            ->where('tasks.id', $id)
            ->join('task_questions', 'task_questions.task_id', '=', 'tasks.id')
            ->select('task_questions.*')
            ->get();

        $the_answers = DB::table('tasks')
            ->where('tasks.id', $id)
            ->join('task_questions', 'task_questions.task_id', '=', 'tasks.id')
            ->join('question_answers', 'question_answers.task_question_id', '=', 'task_questions.id')
            ->select('question_answers.*')
            ->get();

        return response([
            'task' => $the_task,
            'questions' => $the_questions,
            'answers' => $the_answers
        ]);
    }

    public function show_task_for_teacher($id)
    {

        $user_id = Auth::id();

        $teacher_id = DB::table('teachers')
            ->where('teachers.user_id', $user_id)
            ->value('teachers.id');

        $teacher = Student::find($teacher_id);

        if (!$teacher) {

            return response()->json('You are not teacher', 403);
        }

        $check1 = DB::table('tasks')
            ->where('tasks.id', $id)
            ->join('class_subjects', 'class_subjects.id', 'tasks.class_subject_id')
            ->where('class_subjects.teacher_id',$teacher_id)
            ->select('tasks.*')
            ->first();

        if (!$check1) {
            return response()->json(['error' => 'this task is not available to you'], 404);
        }


        $the_task = DB::table('tasks')
            ->where('tasks.id', $id)
            ->join('class_subjects', 'class_subjects.id', 'tasks.class_subject_id')
            ->join('subjects', 'subjects.id', 'class_subjects.subject_id')
            ->join('teachers', 'teachers.id', 'class_subjects.teacher_id')
            ->join('users', 'users.id', 'teachers.user_id')
            ->select('tasks.*', 'subjects.name', 'users.first_name', 'users.last_name')
            ->get();

        $the_questions = DB::table('tasks')
            ->where('tasks.id', $id)
            ->join('task_questions', 'task_questions.task_id', '=', 'tasks.id')
            ->select('task_questions.*')
            ->get();

        $the_answers = DB::table('tasks')
            ->where('tasks.id', $id)
            ->join('task_questions', 'task_questions.task_id', '=', 'tasks.id')
            ->join('question_answers', 'question_answers.task_question_id', '=', 'task_questions.id')
            ->select('question_answers.*')
            ->get();

        return response([
            'task' => $the_task,
            'questions' => $the_questions,
            'answers' => $the_answers
        ]);
    }


    public function solve_task(Request $request)
    {

        $user_id = Auth::id();

        $student_id = DB::table('students')
            ->where('students.user_id', $user_id)
            ->value('students.id');

        $student = Student::find($student_id);

        if (!$student) {

            return response()->json('You are not student', 403);
        }

        $validator = Validator::make($request->all(), [
            'task_id' => 'required|integer',
            'answers' => 'required|array',
            'answers.*.answer_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task = task::where('tasks.finished','1')->find($request->task_id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        
        $check1 = DB::table('tasks')
            ->where('tasks.id', $request->task_id)
            ->join('class_subjects', 'class_subjects.id', 'tasks.class_subject_id')
            ->join('classses', 'classses.id', 'class_subjects.class_id')
            ->where('classses.id', $student->class_id)
            ->select('tasks.*')
            ->first();

        if (!$check1) {
            return response()->json(['error' => 'this task is not available to you'], 404);
        }

        $check2 = DB::table('task_grades')
            ->where('task_grades.task_id', $request->task_id)
            ->join('students', 'students.id', 'task_grades.student_id')
            ->where('students.id', $student_id)
            ->select('task_grades.*')
            ->get();

        if (count($check2) !== 0) {
            return response()->json(['error' => 'you have already solved this task'], 404);
        }


        
        $task_questions = DB::table('task_questions')
            ->where('task_questions.task_id', $request->task_id)
            ->select('task_questions.*')
            ->get();

        $the_grade = 0;
        $correct_answers = [];

        foreach ($task_questions as $i => $task_question) {
            $answer_id = $request->answers[$i]['answer_id'];

            $the_answer = DB::table('question_answers')
                ->where('question_answers.task_question_id', $task_question->id)
                ->where('question_answers.id', $answer_id)
                ->select('question_answers.*')
                ->first();

            if ($the_answer && $the_answer->correct_answer == 1) {
                $the_grade += $task_question->question_grade;
            }

            $correct_answers[$i]=DB::table('question_answers')
            ->where('question_answers.task_question_id', $task_question->id)
            ->join('task_questions','task_questions.id','question_answers.task_question_id')
            ->where('question_answers.correct_answer', '1')
            ->select('question_answers.the_answer','task_questions.the_question')
            ->first();

        }

        task_grade::create([
            'student_id' => $student_id,
            'task_id' => $request->task_id,
            'grade' => $the_grade
        ]);

        return response([
            'grade'=>$the_grade,
             'question and correct answer'=>$correct_answers
        ]);
    }

    public function show_question(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'question_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $t = DB::table('task_questions')
            ->where('task_questions.id', $request->question_id)
            ->join('question_answers', 'question_answers.task_question_id', 'task_questions.id')
            ->select('task_questions.*', 'question_answers.*')
            ->get();

        return response($t);
    }

    public function delete_question($id)
    {
        $user_id = Auth::id();

        $teacher_id = DB::table('teachers')
            ->where('teachers.user_id', $user_id)
            ->value('teachers.id');

        $teacher = Student::find($teacher_id);

        if (!$teacher) {

            return response()->json('You are not teacher', 403);
        }

        $t =task_question::
            where('task_questions.id', $id)
            ->join('tasks', 'tasks.id', 'task_questions.task_id')  
            ->where('tasks.finished','0')
            ->join('class_subjects', 'class_subjects.id', 'tasks.class_subject_id')
            ->join('teachers', 'teachers.id', 'class_subjects.teacher_id')
            ->where('teachers.id', $teacher_id)
            ->select('task_questions.*')
            ->first();
        
        if (!$t) {
            return response()->json('not_found', 400);
        }    

        $t->delete();  

        return response()->json('deleted', 200);
    }

    
    
}
