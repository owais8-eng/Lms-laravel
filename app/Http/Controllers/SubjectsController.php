<?php

namespace App\Http\Controllers;

use App\Models\photos_about_subject;
use App\Models\subject;
use App\Models\student;
use App\Models\teacher;
use App\Models\class_subject;
use App\Models\subject_units;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Psr7\UploadedFile;

class SubjectsController extends Controller
{
    public function store_subject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'the_class' => 'required|integer',
            'about_subject' => 'required|string',
            'total_grade'=>'required|integer',
            'photo_path'=> 'required|image|max:2048',
            'book_path' => 'required|mimes:pdf',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

            $book = $request->file('book_path')->store('images','public');
            $bookUrl = asset('storage/'.$book);

            $photoUrl = null;

          
            if ($request->hasFile('photo_path')){
                $photo_subject_path = $request->photo_path->store('images', 'public');
                $photoUrl = asset('storage/' . $photo_subject_path);
            }


        $request->validate([
            'the_class' => 'required|in:7,8,9',
            'name'=>'required|in:Islamic religious education,arabic,art,Science,history,geography,mathematics,Music,National education,Physics and chemistry,Information and communications technology,Russian,english,french'
        ]);

        $subject=DB::table('subjects')
        ->where('subjects.name',$request->name)
        ->where('subjects.the_class',$request->the_class)
        ->select('subjects.*')
        ->get();

        if(count($subject)!=0){

            return response('this subject already exist');
        }
        $subject = subject::create([
            'name' => $request->name,
            'the_class' => $request->the_class,
            'about_subject' => $request->about_subject,
            'total_grade'=>$request->total_grade,
            'book_path' => $bookUrl,
            'photo_path'=>$photoUrl
        ]);

        
        return response()->json(['the subject added',$subject], 200);
    }


    public function store_subject_units(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id'=>'required|integer',
            'unit_number' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'photo_path' => 'required|image|max:2048'

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        
        $subject=subject::find($request->subject_id);
        if(!$subject){
            return response('this sunject does not exist');
        }


            $photoSubjectUrl = null;

            if ($request->hasFile('photo_path')){
                $photo_subject_path = $request->photo_path->store('images', 'public');
                $photoSubjectUrl = asset('storage/' . $photo_subject_path);
            }

            $subject_unit_check=DB::table('subject_units')
            ->where('subject_units.subject_id',$request->subject_id)
            ->where('subject_units.unit_number',$request->unit_number)
            ->select('subject_units.*')
            ->first();

            if($subject_unit_check){
                return response('this unit alredy exist');
            }
    
            
            $subject_unit = subject_units::create([
                'subject_id' => $request->subject_id,
                'unit_number' => $request->unit_number,
                'title' => $request->title,
                'description' => $request->description,
                'photo_path' => $photoSubjectUrl
            ]);

        return response()->json(['the unit added',$subject_unit], 200);
    }

    public function store_class_subject(Request $request){

        $validator = Validator::make($request->all(), [
            'class_id' => 'required|integer',
            'teacher_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'time_on_sun' => 'nullable|integer',
            'time_on_mon' => 'nullable|integer',
            'time_on_tue' => 'nullable|integer',
            'time_on_wed' => 'nullable|integer',
            'time_on_thu' => 'nullable|integer',
            'exam_date_and_time' => 'nullable|date_format:Y-m-d H:i',

        ]);

    
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $class_level1=DB::table('subjects')
        ->where('subjects.id',$request->subject_id)
        ->value('subjects.the_class');
        $class_level2=DB::table('classses')
        ->where('classses.id',$request->class_id)
        ->where('classses.class_level',$class_level1)
        ->select('classses.class_level')
        ->get();

        if(count($class_level2)==0){
            return response()->json('the subject you are trying to put is not for this class',400);

        }


        $class_subject =DB::table('class_subjects')
        ->where('class_subjects.class_id',$request->class_id)
        ->where('class_subjects.subject_id',$request->subject_id)
        ->select('class_subjects.*')
        ->get();

        if(count($class_subject)!=0){
            return response()->json('already exist',400);
        }

        $teacher=DB::table('teachers')
        ->where('teachers.id',$request->teacher_id)
        ->select('teachers.*')
        ->first();

        if(!$teacher){
            return response()->json('there is no teacher',400);
        }
        $teacher_subject_check =DB::table('class_subjects')
        ->where('class_subjects.teacher_id',$request->teacher_id)
        ->where('class_subjects.class_id',$request->class_id)
        ->value('class_subjects.subject_id');

        if($teacher_subject_check){
            if($teacher_subject_check!==$request->subject_id){
                return response()->json('same teacher can not teach tow subjects fot the same class',400);

            }
       
        }
        $teacher_subject_check1 =DB::table('classses')
        ->where('classses.id',$request->class_id)
        ->where('classses.class_level',$teacher->class_level)
        ->value('classses.class_level');
        
        if(!$teacher_subject_check1){
            return response()->json('this teacher does not teach this class',400);

        }

        
        $check1 = DB::table('class_subjects')
        ->where('class_subjects.class_id', $request->class_id)
        ->where(function ($query) use ($request) {
            $query->where(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_sun', '=', $request->time_on_sun)
                         ->whereNotNull('class_subjects.time_on_sun');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_mon', '=', $request->time_on_mon)
                         ->whereNotNull('class_subjects.time_on_mon');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_tue', '=', $request->time_on_tue)
                         ->whereNotNull('class_subjects.time_on_tue');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_wed', '=', $request->time_on_wed)
                         ->whereNotNull('class_subjects.time_on_wed');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_thu', '=', $request->time_on_thu)
                         ->whereNotNull('class_subjects.time_on_thu');
            });
        })
        ->select('class_subjects.*')
        ->first();

        if($check1){
            return response()->json('you can not store tow subjects at the same time for the same class',400);
        }

        $check2=DB::table('class_subjects')
        ->where('class_subjects.teacher_id', $request->teacher_id)
        ->where(function ($query) use ($request) {
            $query->where(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_sun', '=', $request->time_on_sun)
                         ->whereNotNull('class_subjects.time_on_sun');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_mon', '=', $request->time_on_mon)
                         ->whereNotNull('class_subjects.time_on_mon');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_tue', '=', $request->time_on_tue)
                         ->whereNotNull('class_subjects.time_on_tue');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_wed', '=', $request->time_on_wed)
                         ->whereNotNull('class_subjects.time_on_wed');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_thu', '=', $request->time_on_thu)
                         ->whereNotNull('class_subjects.time_on_thu');
            });
        })
        ->select('class_subjects.*')
        ->first();

        if($check2){
            return response()->json('same techer can not be at tow classes at the same time ',400);
        }

        $check3=DB::table('class_subjects')
        ->where('class_subjects.class_id', $request->class_id)
        ->whereNotNull('class_subjects.exam_date_and_time')
        ->where('class_subjects.exam_date_and_time',$request->exam_date_and_time)
        ->select('class_subjects.*')
        ->first();
        
        if($check3){
            return response()->json('there is alredy an exam in the date and time for this class ',400);

        }
        $class_subject = class_subject::create([
            'class_id' => $request->class_id,
            'teacher_id' =>  $request->teacher_id,
            'subject_id' =>  $request->subject_id,
            'time_on_sun' => $request->time_on_sun,
            'time_on_mon' => $request->time_on_mon,
            'time_on_tue' => $request->time_on_tue,
            'time_on_wed' => $request->time_on_wed,
            'time_on_thu' => $request->time_on_thu,
            'exam_date_and_time' => $request->exam_date_and_time,
        ]);

        return response()->json(['added',$class_subject], 200);

    }

    public function edit_class_subject(Request $request){

        if ($request->exam_date_and_time === '0') {
            $request->merge(['exam_date_and_time' => '3000-01-01 10:20']);
        }
        $validator = Validator::make($request->all(), [
            'class_subject_id' => 'required|integer',
            'teacher_id' => 'nullable|integer',
            'time_on_sun' => 'nullable|integer',
            'time_on_mon' => 'nullable|integer',
            'time_on_tue' => 'nullable|integer',
            'time_on_wed' => 'nullable|integer',
            'time_on_thu' => 'nullable|integer',
            'exam_date_and_time' => 'nullable|date_format:Y-m-d H:i',

        ]);

    
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $class_subject=class_subject::find($request->class_subject_id);

        if(!$class_subject){
            return response('class subject not found');

        }
        if($request->teacher_id){
            $teacher=DB::table('teachers')
            ->where('teachers.id',$request->teacher_id)
            ->select('teachers.*')
            ->first();

            if(!$teacher){
                return response('there is no teacher');
            }

            $class_subject->teacher_id=$request->teacher_id;
        }
    
        

        $check1 = DB::table('class_subjects')
        ->where('class_subjects.class_id', $class_subject->class_id)
        ->where(function ($query) use ($request) {
            $query->where(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_sun', '=', $request->time_on_sun)
                         ->whereNotNull('class_subjects.time_on_sun');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_mon', '=', $request->time_on_mon)
                         ->whereNotNull('class_subjects.time_on_mon');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_tue', '=', $request->time_on_tue)
                         ->whereNotNull('class_subjects.time_on_tue');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_wed', '=', $request->time_on_wed)
                         ->whereNotNull('class_subjects.time_on_wed');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_thu', '=', $request->time_on_thu)
                         ->whereNotNull('class_subjects.time_on_thu');
            });
        })
        ->select('class_subjects.*')
        ->first();

        if($check1){
            return response('you can not store tow subjects at the same time for the same class');

        }

        $check2=DB::table('class_subjects')
        ->where('class_subjects.teacher_id', $class_subject->teacher_id)
        ->where(function ($query) use ($request) {
            $query->where(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_sun', '=', $request->time_on_sun)
                         ->whereNotNull('class_subjects.time_on_sun');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_mon', '=', $request->time_on_mon)
                         ->whereNotNull('class_subjects.time_on_mon');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_tue', '=', $request->time_on_tue)
                         ->whereNotNull('class_subjects.time_on_tue');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_wed', '=', $request->time_on_wed)
                         ->whereNotNull('class_subjects.time_on_wed');
            })
            ->orWhere(function($subQuery) use ($request) {
                $subQuery->where('class_subjects.time_on_thu', '=', $request->time_on_thu)
                         ->whereNotNull('class_subjects.time_on_thu');
            });
        })
        ->select('class_subjects.*')
        ->first();

        if($check2){
            return response('same techer can not be at tow classes at the same time ');

        }

        $check3=DB::table('class_subjects')
        ->where('class_subjects.class_id', $class_subject->class_id)
        ->whereNotNull('class_subjects.exam_date_and_time')
        ->where('class_subjects.exam_date_and_time',$request->exam_date_and_time)
        ->select('class_subjects.*')
        ->first();
        
        if($check3){
            return response('there is alredy an exam in the date and time for this class ');

        }

        if(isset($request->time_on_sun)){
            if($request->time_on_sun==0){
                $class_subject->time_on_sun=null;
            }
            else{
                $class_subject->time_on_sun=$request->time_on_sun;

            }
        }
        if(isset($request->time_on_mon)){
            if($request->time_on_mon==0){
                $class_subject->time_on_mon=null;
            }
            else{
                $class_subject->time_on_mon=$request->time_on_mon;
            }
        }
        if(isset($request->time_on_tue)){
            if($request->time_on_tue==0){
                $class_subject->time_on_tue=null;
            }
            else{
                $class_subject->time_on_tue=$request->time_on_tue;

            }
        }
        if(isset($request->time_on_wed)){
            if($request->time_on_wed==0){
                $class_subject->time_on_wed=null;
            }
            else{
                $class_subject->time_on_wed=$request->time_on_wed;

            }
        }
        if(isset($request->time_on_thu)){
            if($request->time_on_thu==0){
                $class_subject->time_on_thu=null;
            }
            else{
                $class_subject->time_on_thu=$request->time_on_thu;

            }
        } 
        if(isset($request->exam_date_and_time)){
            if($request->exam_date_and_time=='3000-01-01 10:20'){
                $class_subject->exam_date_and_time=null;
            }
            else{
                $class_subject->exam_date_and_time=$request->exam_date_and_time;

            }
        } 

        $class_subject->save();
        return response($class_subject);

        
    }


    public function show_the_schedule_for_student(){

        $user_id = Auth::id();

        $student_id=DB::table('students')
        ->where('students.user_id',$user_id)
        ->value('students.id');

        $student = Student::find($student_id);

        if (!$student) {

            return response()->json('you are not auth as a student', 403);
        }

        $schedule=DB::table('class_subjects')
        ->where('class_subjects.class_id',$student->class_id)
        ->join('subjects','subjects.id','class_subjects.subject_id')
        ->select('subjects.name','class_subjects.*')
        ->get();

        if(count($schedule)==0){
            return response('there is no schedule');
        }
        return response($schedule);

    }
  
    public function show_the_schedule_for_teacher(){

        $user_id = Auth::id();

        $teacher_id=DB::table('teachers')
        ->where('teachers.user_id',$user_id)
        ->value('teachers.id');

        $teacher = teacher::find($teacher_id);

        if (!$teacher) {

            return response()->json('you are not auth as a teacher', 403);
        }

        $schedule=DB::table('class_subjects')
        ->where('class_subjects.teacher_id',$teacher_id)
        ->join('subjects','subjects.id','class_subjects.subject_id')
        ->join('classses','classses.id','class_subjects.class_id')
        ->select('classses.class_level','classses.class_number','subjects.name','class_subjects.*')
        ->get();

        if(count($schedule)==0){
            return response('there is no schedule');
        }
        return response($schedule);

    }
    public function show_subjects_of_the_class(Request $request)
    {
        $the_class=$request->input('class_level');
        $subjects = DB::table('subjects')
            ->where('the_class', $the_class)
            ->get();
        return response()->json($subjects, 200);
    }
    public function show_all_subjects(Request $request)
    {
        $subjects = DB::table('subjects')
        ->get();
        if(count($subjects)==0){
            return response('there is no teachers');
        }
        return response()->json($subjects, 200);
    }
    public function show_subject(Request $request)
    {
        $id=$request->input('subject_id');

        $subject = DB::table('subjects')
            ->where('subjects.id', $id)
            ->select('subjects.*')
            ->get();

        $subject_units = DB::table('subjects')
        ->where('subjects.id', $id)
        ->join('subject_units', 'subject_units.subject_id', '=', 'subjects.id')
        ->select('subject_units.*')
        ->get();

      

        return response()->json([
            'the subject'=>$subject,
            'units'=>$subject_units,
        ], 200);
    }

    public function delete_subject($id){
        $subject=subject::find($id);

        if(!$subject){
            return response()->json([
                'message' => 'subject not found',

            ]);       
        }

        $subject->delete();
        return response()->json([
            'message' => 'subject deleted successfully',

        ]);  

    }

    public function edit_subject(Request $request){

        
        $validator = Validator::make($request->all(), [
            'subject_id'=>'required|integer',
            'photo_path'=> 'nullable|image|max:2048',
            'book_path' => 'nullable|mimes:pdf',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $subject=subject::find($request->subject_id);

        if(!$subject){
            return response()->json([
                'message' => 'subject not found',

            ]);       
        }
        $bookUrl=null;
        if ($request->hasFile('book_path')){

            $book = $request->file('book_path')->store('images','public');
            $bookUrl = asset('storage/'.$book);
            $subject->book_path=$bookUrl;
            $subject->save();
        }

        $photoUrl = null; 
        if ($request->hasFile('photo_path')){

            $photo_subject_path = $request->photo_path->store('images', 'public');
            $photoUrl = asset('storage/' . $photo_subject_path);
            $subject->photo_path=$photoUrl;
            $subject->save();
        }

        return response()->json([
            $subject

        ]);   

       
    }
}