<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\advertisement;
use App\Models\classs;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\NotificationsTrait;


class AdvertisementController extends Controller
{
    use NotificationsTrait;

    public function index()
    {
        $advertisements = Advertisement::all();
        return response()->json($advertisements);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_level' => 'required',
            'class_number' => 'required|numeric',
            'type'=>'required|string',
            'photo_path' => 'required|image|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $request->validate([
            'class_level' => 'required|in:7,8,9',
            'type' => 'required|in:bus,trips,wallet,exam,results,instuctions,other',
        ]);
       
        
        $class_level = $request->input('class_level');
        $class_number = $request->input('class_number');


        $photo_path = $request->file('photo_path')->store('images','public');

        $imageUrl = asset('storage/'.$photo_path);

        $class = Classs::where('class_level', $class_level)
            ->where('class_number', $class_number)
            ->first();

        if (!$class){

            return response('this class does not exist ');
        }
        $advertisement = advertisement::create([
            'class_id' => $class->id,
            'type'=>$request->type,
            'photo_path' => $imageUrl,
        ]);

        $tokens = User::
        join('students','students.user_id','users.id')
        ->where('students.class_id', $class->id)
        ->pluck('fcm_token')->toArray(); 
        
        $title = 'New Advertisement';
        $body = 'A new advertisement has been posted.';
        
        $this->sendNotification($title, $body,$tokens); 
    
      
        return response()->json([
            'advertisements' => $advertisement,
        ], 200);
    }
    public function show(Request $request)
    {
        $id=$request->input('advertisement_id');

        $advertisements = advertisement::find($id);
        if (!$advertisements) {
            return response()->json([
                'message' => 'not found ',

            ], 200);
        }
        return response()->json([
            'message' => 'retruved successfully',
            'data' => $advertisements,
        ], 200);
    }

    public function show_all_by_class()
    {
        $user_id = Auth::id();
        $class_id = DB::table('students')
        ->where('students.user_id',$user_id)
        ->value('students.class_id');


        if (!$class_id) {
            return response()->json([
                'message' => 'this class does not exist',

            ], 200);
        }

        $advertisements=DB::table('advertisements')
        ->where('advertisements.class_id',$class_id)
        ->select('advertisements.*')
        ->get();

        if (count($advertisements)==0) {
            return response()->json([
                'message' => 'not found any Advertisements',

            ], 200);
        }

        return response()->json([
            'message' => 'retruved successfully',
            'data' => $advertisements,
        ], 200);
    }

    public function show_all_by_class_by_type(Request $request)
    {

        $validator = Validator::make($request->all(), [
        
            'type'=>'required|string',
        ]);

        $request->validate([

            'type' => 'required|in:bus,trips,wallet,exam,results,instuctions,other',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user_id = Auth::id();
        $class_id = DB::table('students')
        ->where('students.user_id',$user_id)
        ->value('students.class_id');


        if (!$class_id) {
            return response()->json([
                'message' => 'this class does not exist',

            ], 200);
        }

        

        $advertisements=DB::table('advertisements')
        ->where('advertisements.class_id',$class_id)
        ->where('advertisements.type',$request->type)
        ->select('advertisements.*')
        ->get();

        if (count($advertisements)==0) {
            return response()->json([
                'message' => 'not found any Advertisements',

            ], 200);
        }

        return response()->json([
            'message' => 'retruved successfully',
            'data' => $advertisements,
        ], 200);
    }
    public function destroy($id)
    {
        $advertisements = advertisement::find($id);
        if (!$advertisements) {

            return response()->json([
                'message' => 'advertisement not found',

            ]);
        }
        $advertisements->delete();
        return response()->json([
            'message' => 'advertisements deleted successfully',
        ]);
    }

    public function show_all_by_class_by_type_for_admin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            
            'class_level' => 'required|integer',
            'class_number' => 'required|integer',
            'type'=>'required|string',
        ]);

        $request->validate([

            'type' => 'required|in:bus,trips,wallet,exam,results,instuctions,other,all',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $class_id=classs::
        where('classses.class_level',$request->class_level)
        ->where('classses.class_number',$request->class_number)
        ->value('classses.id');
        
        if(!$class_id){
            return response()->json('class not found', 400);
        }

        if($request->type=='all'){

            $advertisements=DB::table('advertisements')
            ->where('advertisements.class_id',$class_id)
            ->select('advertisements.*')
            ->get();
    
        }

        else{

            $advertisements=DB::table('advertisements')
            ->where('advertisements.class_id',$class_id)
            ->where('advertisements.type',$request->type)
            ->select('advertisements.*')
            ->get();
    
        }
        if (count($advertisements)==0) {
            return response()->json([
                'message' => 'ther is no Advertisement',

            ], 400);
        }

        return response()->json([
            'message' => 'retruved successfully',
            'data' => $advertisements,
        ], 200);
    }
}

