<?php

namespace App\Http\Controllers;

use App\Models\subject;
use App\Models\classs;
use App\Models\class_subject;
use App\Models\grade;
use App\Models\test;
use illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use PHPUnit\Event\Code\Test as CodeTest;


class TestController extends Controller
{
    //test method
    public function index()
    {
        $tests = Test::with(['class_subject' => function ($query) {
            $query->select('class_id')->with(['class' => function ($query) {
                $query->select('class_level', 'class_number');
            }]);
        }])->get();



        return response()->json($tests);
    }
    public function show_test_by_class_level(Request $request)
    {

        $class_level = $request->input('class_level');
        $tests = Test::whereHas('class_subject', function ($query) use ($class_level) {
            $query->whereHas('class', function ($query) use ($class_level) {
                $query->where('class_level', $class_level);
            });
        })->with(['class_subject' => function ($query) {
            $query->select('class_id', 'subject_id')->with(['class' => function ($query) {
                $query->select('class_level', 'class_number');
            }, 'subject']);
        }])->get();

        return response()->json($tests);
    }
    public function store_test(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_level' => 'required|integer',
            'class_number' => 'required|integer',
            'subject_name'=> 'required|string',
            'exam_paper_path'=> 'nullable|image|max:2048',
        ]);
        
        $request->validate([
            'class_level' => 'required|in:7,8,9',
        ]);
        $request->validate([
            'type' => 'required|in:exam,oral_exam,homework,quiz',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $photoUrl = null;
                  
        if ($request->hasFile('exam_paper_path')){
            $exam_paper_path = $request->exam_paper_path->store('images', 'public');
            $photoUrl = asset('storage/' . $exam_paper_path);
        }

        $class_level = $request->class_level;
        $class_number = $request->class_number;
        $subject_name = $request->subject_name;


        $class_id = DB::table('classses')
            ->where('classses.class_level', $class_level)
            ->where('classses.class_number', $class_number)
            ->value('classses.id');


        $subject_id = DB::table('subjects')
            ->where('subjects.name', $subject_name)
            ->where('subjects.the_class', $class_level)
            ->value('subjects.id');

       $class_subject_id = DB::table('class_subjects')
            ->where('class_subjects.class_id', $class_id)
            ->where('class_subjects.subject_id', $subject_id)
            ->value('class_subjects.id');
        if (!$class_subject_id) {
            return response('this subject is not for this class');
        }

        $test = Test::create([
            'class_subject_id' => $class_subject_id,
            'type' => $request->input('type'),
            'exam_paper_path' => $photoUrl,
        ]);

        return response()->json([
            'message' => 'Test created successfully',
            'class level' => $class_level,
            'class number' => $class_number,
            'test' => $test,
        ], 201);
    }
    public function delete_test($id)
    {

        $tests = test::find($id);
        if (!$tests) {

            return response()->json([
                'message' => 'test not found',

            ]);
        }
        $tests->delete();
        return response()->json([
            'message' => 'tests deleted successfully',
        ]);
    }

}
