<?php

namespace App\Http\Controllers;

use App\Models\student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\grade;
use App\Models\subject;
use App\Models\classs;
use App\Models\class_subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Util\ExcludeList;
use App\Http\Traits\NotificationsTrait;


class GradesController extends Controller
{
    use NotificationsTrait;

    public function store_grade_test(Request $request)
    {
        $e = $request->all();
        $validator = Validator::make($e, [
            'student_id' => 'required',
            'test_id' => 'required',
            'grade' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $grade = DB::table('grades')
            ->where('grades.student_id', $request->student_id)
            ->where('grades.test_id', $request->test_id)
            ->select('grades.*')
            ->first();

        if ($grade) {
            return response('already exist');
        }

        $check_class1 = DB::table('students')
            ->where('students.id', $request->student_id)
            ->value('students.class_id');

        $check_class2 = DB::table('tests')
            ->where('tests.id', $request->test_id)
            ->join('class_subjects', 'tests.class_subject_id', 'class_subjects.id')
            ->value('class_subjects.class_id');


        if ($check_class1 != $check_class2) {
            return response('this student is not in the correct class');
        }

        $total_grade = DB::table('tests')
            ->where('tests.id', $request->test_id)
            ->join('class_subjects', 'class_subjects.id', 'tests.class_subject_id')
            ->join('subjects', 'subjects.id', 'class_subjects.subject_id')
            ->value('subjects.total_grade');

        $grade_type = DB::table('tests')
            ->where('tests.id', $request->test_id)
            ->value('tests.type');

        if ($grade_type == 'homework' || $grade_type == 'oral_exam' || $grade_type == 'quiz') {
            $total_grade_by_type = 0.2 * $total_grade;
        }

        if ($grade_type == 'exam') {
            $total_grade_by_type = 0.4 * $total_grade;
        }

        if ($request->grade > $total_grade_by_type) {
            return response('this grade is bigger than the total grade');
        }
        $grade = grade::create([
            'student_id' => $request->student_id,
            'test_id' => $request->test_id,
            'grade' => $request->grade,
        ]);


        $tokens = User::
        join('students','students.user_id','users.id')
        ->where('students.id', $request->student_id)
        ->pluck('fcm_token')->toArray(); 
        
        $title = 'New Grade';
        $body = 'A new grade has been posted.';
        
        $this->sendNotification($title, $body,$tokens); 
    

        return response()->json([
            'grade' => $grade,
            'total_grade' => $total_grade_by_type,

        ]);
    }
    public function show_grade_by_type(Request $request)
    {

        $type = $request->input('type');
        $subject_name = $request->input('subject_name');
        $user_id = Auth::id();
        $student_id = DB::table('students')
            ->where('students.user_id', $user_id)
            ->value('students.id');

        $grades_by_type = DB::table('grades')
            ->where('grades.student_id', $student_id)
            ->join('tests', 'tests.id', 'grades.test_id')
            ->join('class_subjects', 'class_subjects.id', 'tests.class_subject_id')
            ->join('subjects', 'subjects.id', 'class_subjects.subject_id')
            ->where('subjects.name', $subject_name)
            ->where('tests.type', $type)
            ->select('grades.*', 'tests.*')
            ->get();

        return response($grades_by_type);
    }

    public function show_the_total_grade()
    {

        $output = [];
        $user_id = Auth::id();
        $student_id = DB::table('students')
            ->where('students.user_id', $user_id)
            ->value('students.id');

        $types = ['exam', 'quiz', 'homework', 'oral_exam'];

        $avg_by_type = [];
        $subjects = Subject::all();

        // Get all grades with related tables in a single query
        $grades = DB::table('grades')
            ->where('grades.student_id', $student_id)
            ->join('tests', 'tests.id', '=', 'grades.test_id')
            ->join('class_subjects', 'class_subjects.id', '=', 'tests.class_subject_id')
            ->join('subjects', 'subjects.id', '=', 'class_subjects.subject_id')
            ->select('subjects.name as subject_name', 'tests.type as test_type', 'grades.grade')
            ->get();

        // Group grades by subject and type
        $groupedGrades = [];
        foreach ($grades as $grade) {
            $groupedGrades[$grade->subject_name][$grade->test_type][] = $grade->grade;
        }


        // Calculate the averages
        foreach ($subjects as $subject) {
            $subjectName = $subject->name;
            $total_garde[$subjectName] = $subject->total_grade;
            foreach ($types as $type) {

                $avg_by_type[$subjectName][$type] = isset($groupedGrades[$subjectName][$type])
                    ? array_sum($groupedGrades[$subjectName][$type]) / count($groupedGrades[$subjectName][$type])
                    : null; // or 0 or another default value
                $weight = 0;
                if (in_array($type, ['quiz', 'oral_exam', 'homework'])) {
                    $weight = 0.2;
                } elseif ($type == 'exam') {
                    $weight = 0.4;
                }

                // Calculate the total grade by type with the weight
                $total_grade_by_type[$subjectName][$type] = $total_garde[$subjectName] * $weight;
            }
        }
        $output = [];
        $gradesNames = $grades->pluck('subject_name')->unique()->values()->all();
        foreach ($gradesNames as $key => $name) {

            $output[$key]['name'] = $name;
            $output[$key]['total_grade'] = $total_garde[$name];
            $output[$key]['avg'] = $avg_by_type[$name];
            $output[$key]['avg_sum'] = array_sum($output[$key]['avg']);
            $output[$key]['total_grade_by_type'] = $total_grade_by_type[$name];
        }

        return response(
            $output

        );
    }
    public function delete_grade($grade_id)
    {

        $grade = grade::where('id', $grade_id)->first();

        if (!$grade) {

            return response()->json([
                'message' => 'grade not found',

            ]);
        }
        $grade->delete();
        return response()->json([
            'message' => 'grade deleted successfully',
        ]);
    }

    public function rank()
    {
        $user_id = Auth::id();

        $student_id = DB::table('students')
            ->where('students.user_id', $user_id)
            ->value('students.id');

        $student = Student::find($student_id);

        if (!$student) {

            return response()->json('You are not student', 403);
        }

        $students_at_the_same_class = DB::table('students')
            ->where('students.class_id', $student->class_id)
            ->select('students.*')
            ->get();

        $class_level = DB::table('classses')
            ->where('classses.id', $student->class_id)
            ->value('classses.class_level');

        $class_level = (string) $class_level;


        $subjects = DB::table('subjects')
            ->where('subjects.the_class', $class_level)
            ->select('subjects.*')
            ->get();


        $students = DB::table('students')
            ->where('students.class_id', $student->class_id)
            ->join('users', 'users.id', 'students.user_id')
            ->select('students.id', 'users.first_name', 'users.last_name', 'users.profile_picture_path')
            ->get();

        $students = $students->keyBy('id')->toArray();

        $grades1 = [];
        $mine = [];
        foreach ($students_at_the_same_class as $student1) {


            $student1_id = $student1->id;
            $types = ['exam', 'quiz', 'homework', 'oral_exam'];

            if ($student1_id == $student_id) {
                $mine[$student1_id] = true;
            } else {
                $mine[$student1_id] = false;
            }
            $avg_by_type = [];


            // Get all grades with related tables in a single query
            $grades = DB::table('grades')
                ->where('grades.student_id', $student1->id)
                ->join('tests', 'tests.id', '=', 'grades.test_id')
                ->join('class_subjects', 'class_subjects.id', '=', 'tests.class_subject_id')
                ->join('subjects', 'subjects.id', '=', 'class_subjects.subject_id')
                ->select('subjects.name as subject_name', 'tests.type as test_type', 'grades.grade')
                ->get();

            // Group grades by subject and type
            $groupedGrades = [];
            foreach ($grades as $grade) {
                $groupedGrades[$student1_id][$grade->subject_name][$grade->test_type][] = $grade->grade;
            }

            // Calculate the averages
            $student_grade_in_subject = [];
            foreach ($subjects as $subject) {
                $subjectName = $subject->name;
                $total_garde[$subjectName] = $subject->total_grade;
                foreach ($types as $type) {

                    $avg_by_type[$student1_id][$subjectName][$type] = isset($groupedGrades[$student1_id][$subjectName][$type])
                        ? array_sum($groupedGrades[$student1_id][$subjectName][$type]) / count($groupedGrades[$student1_id][$subjectName][$type])
                        : null; // or 0 or another default value

                }
                $student_grade_in_subject[$student1_id][$subjectName] = array_sum($avg_by_type[$student1_id][$subjectName]);
            }

            $grades1[$student1_id] = array_sum($student_grade_in_subject[$student1_id]);
            arsort($grades1);
        }

        $total_garde1 = array_sum($total_garde);

        $response_data = [
            'total_grade' => $total_garde1,
            'grades' => $grades1,
            'mine' => $mine,
            'students' => $students
        ];

        foreach ($students as $student2) {
            $student2_id = $student2->id;
            $st[$student2_id] = [
                $total_garde1,
                $grades1[$student2_id],
                $mine[$student2_id],
                $students[$student2_id]
            ];
        }
        arsort($st);
        $convertedData = array_map(function ($item) {
            return [
                'total_grade' => $item[0],
                'grade' => $item[1],
                'mine' => $item[2],
                'student' => $item[3]
            ];
        }, $st);
        return response()->json(array_values((array) $convertedData));
    }

    public function show_student_grade_for_admin(Request $request){
        $e = $request->all();
        $validator = Validator::make($e, [
            
            'student_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $student=student::find($request->student_id);

        
        $output = [];
        $types = ['exam', 'quiz', 'homework', 'oral_exam'];

        $avg_by_type = [];
        $subjects = Subject::all();

        // Get all grades with related tables in a single query
        $grades = DB::table('grades')
            ->where('grades.student_id', $student->id)
            ->join('tests', 'tests.id', '=', 'grades.test_id')
            ->join('class_subjects', 'class_subjects.id', '=', 'tests.class_subject_id')
            ->join('subjects', 'subjects.id', '=', 'class_subjects.subject_id')
            ->select('subjects.name as subject_name', 'tests.type as test_type', 'grades.grade')
            ->get();

        // Group grades by subject and type
        $groupedGrades = [];
        foreach ($grades as $grade) {
            $groupedGrades[$grade->subject_name][$grade->test_type][] = $grade->grade;
        }


        // Calculate the averages
        foreach ($subjects as $subject) {
            $subjectName = $subject->name;
            $total_garde[$subjectName] = $subject->total_grade;
            foreach ($types as $type) {

                $avg_by_type[$subjectName][$type] = isset($groupedGrades[$subjectName][$type])
                    ? array_sum($groupedGrades[$subjectName][$type]) / count($groupedGrades[$subjectName][$type])
                    : null; // or 0 or another default value
                $weight = 0;
                if (in_array($type, ['quiz', 'oral_exam', 'homework'])) {
                    $weight = 0.2;
                } elseif ($type == 'exam') {
                    $weight = 0.4;
                }

                // Calculate the total grade by type with the weight
                $total_grade_by_type[$subjectName][$type] = $total_garde[$subjectName] * $weight;
            }
        }
        $output = [];
        $gradesNames = $grades->pluck('subject_name')->unique()->values()->all();
        foreach ($gradesNames as $key => $name) {

            $output[$key]['name'] = $name;
            $output[$key]['avg'] = $avg_by_type[$name];
            $output[$key]['avg_sum'] = array_sum($output[$key]['avg']);
            $output[$key]['total_grade'] = $total_garde[$name];
            $output[$key]['total_grade_by_type'] = $total_grade_by_type[$name];
        }
        $output1 = [];

        $output1['his_grade'] = array_sum(array_column($output, 'avg_sum')); // Sum all 'avg_sum' values
        $output1['total_grade'] = array_sum(array_column($output, 'total_grade')); // Sum all 'total_grade' values
            
        

        return response(
            $output1

        );


        
    } 
}
