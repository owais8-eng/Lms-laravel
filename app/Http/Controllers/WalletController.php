<?php

namespace App\Http\Controllers;

use App\Models\about_wallet;
use App\Models\classs;
use App\Models\student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewFee;
use App\Models\Fee;
use App\Http\Traits\NotificationsTrait;



class WalletController extends Controller
{
    use NotificationsTrait;

    public function create_fee(Request $request)
    {
        // Validate request input
        $validator = Validator::make($request->all(),[
            'fee_name' => 'required|string',
            'benefits' => 'required|numeric|min:0',
            'type'=>'required|string',
        ]);
        

        if($validator->fails())
        {
            return response()->json($validator->errors());
        }
        $request->validate([
            'type' => 'required|in:bus,school,other',
        ]);
        
    
        // Extract input data from request
        $fee_name = $request->input('fee_name');
        $benefits = $request->input('benefits');
        $type = $request->input('type');
        $due_date = now()->addMonth()->format('Y-m-d');
    
        // Create the fee record
        $fee = Fee::create([
            'fee_name' => $fee_name,
            'benefits' => $benefits,
            'type' => $type,
            'due_date' => $due_date,
        ]);
    
        $students = Student::get();
    
        foreach ($students as $student) {

            if($type=='school'){
                $student->remain += $benefits;
                $student->save();
            $students_id[$student->id]=$student->id ;  
            }

            else if ($type=='bus' && $student->bus=='1'){
                $student->remain += $benefits;
                $student->save();
                $students_id[$student->id]=$student->id ;  

            }
            else if($type!=='bus'){
                $students_id[$student->id]=$student->id ;  

            }
       
        }
        $st=array_values($students_id);
        
        $tokens = User::
        join('students','students.user_id','users.id')
        ->where('students.id',$st)
        ->pluck('fcm_token')->toArray(); 
        $title = 'New Fee';
        $body = 'A new fee has been added.';
        
        $this->sendNotification($title, $body,$tokens); 
    

        return response()->json([
            'message' => trans('success'),
            'data' => $fee,
        ]);
    }
    
    public function deposit_wallet(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
        ]);

        $student = Student::find($request->input('student_id'));

        if (!$student) {
            return response()->json([
                'message' => 'student not found',
            ], 404);
        }

        $amount = $request->input('amount');

        $student->wallet_balance += $amount;
        $student->save();

        $wallet_info = about_wallet::create([
            'student_id' => $student->id,
            'amount' => $amount,
            'description'=>'deposit',
            'fee_id'=>null
        ]);

        $tokens = User::
        join('students','students.user_id','users.id')
        ->where('students.id',$student->id)
        ->pluck('fcm_token')->toArray(); 
        $title = 'Deposit';
        $body = 'Deposit has been made to your account';
        
        $this->sendNotification($title, $body,$tokens); 
    

        return response()->json([
            'message' => 'deposit successfully',
            'data' => $wallet_info,
        ]);
    }

    public function show_fees(){

        $user_id = Auth::id();

        $student_id=DB::table('students')
        ->where('students.user_id',$user_id)
        ->value('students.id');

        $student = Student::find($student_id);

        if (!$student) {

            return response()->json('You are not student', 403);
        }

    
        $student_bus=$student->bus;
        if($student_bus){
            $the_fees=DB::table('fees')
            ->select('fees.*')
            ->get();
        }

        else{
            $the_fees=DB::table('fees')
            ->whereIn('fees.type', ['other', 'school'])
            ->select('fees.*')
            ->get();
        }
        $fees_array = $the_fees->toArray();

        foreach ($fees_array as $key => $fee) {
            $check = DB::table('about_wallets')
                ->where('about_wallets.student_id', $student->id)
                ->where('about_wallets.fee_id', $fee->id)
                ->select('about_wallets.*')
                ->first();
    
            if ($check) {
                unset($fees_array[$key]);
            }
        }


        $fees_list = array_values($fees_array);

        return response()->json($fees_list);
    
    }
    public function paid_fees(Request $request)
    {
      $request->validate([
        'fee_id'=>'required|numeric|min:0'
        ]);

        $user_id = Auth::id();

        $student_id=DB::table('students')
        ->where('students.user_id',$user_id)
        ->value('students.id');

        $student = Student::find($student_id);

        if (!$student) {

            return response()->json('You are not student', 403);
        }

        $the_fee=DB::table('fees')
        ->where('fees.id',$request->fee_id)
        ->select('fees.*')
        ->first();
        
        if(!$the_fee){
            return response()->json('this fee does not exist ', 403);

        }

        $check=DB::table('about_wallets')
        ->where('about_wallets.student_id',$student->id)
        ->where('about_wallets.fee_id',$request->fee_id)
        ->select('about_wallets.*')
        ->first();

        if($check){
            return response()->json('You already paid this fee , thanks', 403);

        }  

        $amount = $the_fee->benefits;
        $description='withdraw';

        // Check if the user has already paid the fee
        
        if ($student->wallet_balance >= $amount) {

            if($the_fee->type =='school'){
                $student->remain -= $amount;
                $student->wallet_balance -= $amount;
                $student->save();

            }
            else if($the_fee->type =='bus' && $student->bus =='1'){
                $student->remain -= $amount;
                $student->wallet_balance -= $amount;
                $student->save();

            }
            else if($the_fee->type =='bus' && $student->bus =='0'){
                return response('you are not in the bus , you can not paid for it ');
            }
            else if ($the_fee->type == 'other'){
                $student->wallet_balance -= $amount;
                $student->save();

            }


            // Save the updated benifats value
        

        $wallet_info =   about_wallet::create([
            'student_id' => $student->id,
            'description' => $description,
            'fee_id'=>$request->fee_id,
            'amount' => $amount,

        ]);


        return response()->json([
            'message' => 'success operation',
            'data' => $wallet_info,
        ]);
        }
        else {
            return response()->json([
                'message' => 'you do not have enough money for this operation please deposit in your wallet'
            ]);
        }
    }
    public function show()
    {
        $user_id = Auth::id();
        $studentId = DB::table('students')
            ->where('user_id', $user_id)
            ->value('id');
        $balance = student::where('id', $studentId)->get(['wallet_balance','remain']);
        $withdraw = DB::table('about_wallets')
        ->join('fees','fees.id','about_wallets.fee_id')
        ->where('about_wallets.student_id', $studentId)
        ->select('fees.*','about_wallets.*')
        ->get();
    
        

        $deposit= DB::table('about_wallets')
        ->where('student_id', $studentId)
        ->where('about_wallets.description','deposit')
        ->select('about_wallets.*')
        ->get();

        return response()->json([
            'balance' => $balance,
            'withdraw' => $withdraw,
            'deposit'=>$deposit
        ]);
    }

    public function all_wallet_balance()
    {      

        $class_ids=DB::table('classses')
        ->pluck('classses.id');
        
        
        if ($class_ids->isEmpty()) {
            return response()->json('There are no classes', 400);
        }

        $sum_balance = student::sum('wallet_balance');

        $balances = DB::table('students')
        ->select('class_id', DB::raw('SUM(wallet_balance) as total_balance'))
        ->whereIn('class_id', $class_ids)
        ->groupBy('class_id')
        ->get();

        $sum_balance_by_class=[] ;

        $sum_balance_by_class['all'] =  $sum_balance;

        foreach ($balances as $balance) {

            $class_level = classs::where('id',$balance->class_id)->value('class_level'); 
            $class_number = classs::where('id',$balance->class_id)->value('class_number');       
            $formatted_key = $class_level . '-' . $class_number;
    
            // Add the balance to the array with the formatted key
            $sum_balance_by_class[$formatted_key] = $balance->total_balance;
        }
    

    if ($sum_balance === 0) {
        return response()->json('There are no students with wallet balances', 400);
    }
        return response()->json([
            $sum_balance_by_class
        ],200);
    }

    public function show_wallet_details_for_admin(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            
            'student_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $student = student::find($request->student_id);
        if(!$student){
            return response()->json('student not found', 400);
        }
        $balance = student::where('id', $request->student_id)->get(['wallet_balance','remain']);
        $withdraw = DB::table('about_wallets')
        ->join('fees','fees.id','about_wallets.fee_id')
        ->where('about_wallets.student_id', $request->student_id)
        ->select('fees.*','about_wallets.*')
        ->get();
    
        

        $deposit= DB::table('about_wallets')
        ->where('student_id', $request->student_id)
        ->where('about_wallets.description','deposit')
        ->select('about_wallets.*')
        ->get();

        return response()->json([
            'balance' => $balance,
            'withdraw' => $withdraw,
            'deposit'=>$deposit
        ],200);
    }



}
