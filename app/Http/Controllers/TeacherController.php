<?php

namespace App\Http\Controllers;

use App\Models\classs;
use App\Models\subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function show_list_of_all_teachers_for_admin(){

       
       
        $teachers = DB::table('users')
        ->join('teachers','teachers.user_id','users.id')
        ->select('teachers.id','users.first_name','users.last_name','teachers.specialization')
        ->get();

        if(count($teachers)==0){
            return response()->json('there is no teacher for this class', 400);
        }

        return response()->json($teachers, 200);
    }
    public function show_all_teachers(Request $request){

        $validator = Validator::make($request->all(), [
            'class_level' => 'required|integer',
            'class_number' => 'required|integer',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $class_id=classs::
        where('classses.class_level',$request->class_level)
        ->where('classses.class_number',$request->class_number)
        ->value('classses.id');
        
        if(!$class_id){
            return response()->json('class not found', 400);
        }

        $teachers = DB::table('users')
        ->join('teachers','teachers.user_id','users.id')
        ->join('class_subjects','class_subjects.teacher_id','teachers.id')
        ->where('class_subjects.class_id',$class_id)
        ->select('users.*','teachers.*')
        ->get();

        if(count($teachers)==0){
            return response()->json('there is no teacher for this class', 400);
        }

        return response()->json($teachers, 200);
    }

    public function show_classes_for_teacher(){

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
            return response()->json($class_subject,200);

    }

    public function show_students_by_class_for_teacher(Request $request){

        $validator = Validator::make($request->all(), [
            'class_id' => 'required|integer',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
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
            ->where('classses.id',$request->class_id)
            ->select('class_subjects.*')
            ->first();

        if(!$class_subject){

            return response()->json('this class is not for you', 403);

        }
        
        $students=DB::table('students')
        ->where('students.class_id',$request->class_id)
        ->join('users','users.id','students.user_id')
        ->select('students.id','users.first_name','users.last_name','users.profile_picture_path')
        ->get();

        if(count($students)==0){
            return response()->json('this class is empty',404);

        }

        $total_grade_in_this_subject=subject::
        where('subjects.id',$class_subject->subject_id)
        ->value('subjects.total_grade');

        $avg_by_type=[];
        $student_grade_in_subject = [];

        foreach ($students as $student) {

            $student_id = $student->id;
            $types = ['exam', 'quiz', 'homework', 'oral_exam'];


            // Get all grades with related tables in a single query
            $grades = DB::table('grades')
                ->where('grades.student_id', $student->id)
                ->join('tests', 'tests.id', '=', 'grades.test_id')
                ->join('class_subjects', 'class_subjects.id', '=', 'tests.class_subject_id')
                ->join('subjects', 'subjects.id', '=', 'class_subjects.subject_id')
                ->where('subjects.id', '=', $class_subject->subject_id)
                ->select('subjects.name as subject_name', 'tests.type as test_type', 'grades.grade')
                ->get();

            // Group grades by subject and type
            $groupedGrades = [];
            foreach ($grades as $grade) {
                $groupedGrades[$student_id][$grade->test_type][] = $grade->grade;
            }

            // Calculate the averages

            foreach ($types as $type) {

                $avg_by_type[$student_id][$type] = isset($groupedGrades[$student_id][$type])
                    ? array_sum($groupedGrades[$student_id][$type]) / count($groupedGrades[$student_id][$type])
                    : null; // or 0 or another default value

            }
                $student_grade_in_subject[$student_id] = array_sum($avg_by_type[$student_id]);

               
        }
        $students = $students->keyBy('id')->toArray();
        foreach ($students as $student) {
            $student_id = $student->id;
            $st[$student_id] = [
            $total_grade_in_this_subject,
            $student_grade_in_subject[$student_id],
            $students[$student_id]
            ];
        }
        arsort($st);
        $convertedData = array_map(function ($item) {
            return [
                'total_grade' => $item[0],
                'grade' => $item[1],
                'student' => $item[2]
            ];
        }, $st);
        return response()->json(array_values((array) $convertedData));



    }

    public function search_for_student(Request $request){
        $request->validate([
            'name' => 'required|string|min:1',
        ]);

        $name = $request->input('name');

        $students = Student::
        join('users','users.id','students.id')
        ->where('users.first_name', 'like', "%{$name}%")
        ->select('students.id','students.user_id','users.first_name','users.last_name')
        ->get();

        return response()->json($students);
    
    }

}
