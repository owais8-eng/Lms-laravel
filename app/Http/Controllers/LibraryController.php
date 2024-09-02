<?php

namespace App\Http\Controllers;

use App\Models\favorite_book;
use App\Models\student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\library;
use Illuminate\Support\Facades\Storage;


class LibraryController extends Controller

{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'book_name' =>'required|string',
            'book_path'=>'required|mimes:pdf',
            'photo_path'=>'required|image|max:2048',
            'type' =>'required|string',

        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors());
        }

        if($request->hasFile('book_path')){
                    
            $book_path = $request->file('book_path')->store('images','public');
            $bookUrl = asset('storage/'.$book_path);

        }


        $book_name=DB::table('libraries')
        ->where('libraries.book_name',$request->book_name)
        ->value('libraries.book_name');

        if($book_name){
            return response('this book is already in the library');
        }

        $imageUrl=null;
        if($request->hasFile('photo_path')){
            $photo_path = $request->file('photo_path')->store('images','public');
            $imageUrl = asset('storage/'.$photo_path);
    
        }
      
        $library=library::create([
            'book_name' => $request->book_name,
            'book_path' => $bookUrl,
            'photo_path'=>$imageUrl,
            'type' => $request->type,
        ]);
        return response()->json($library,200);
    }

    public function show_educational()
    {
        $books = library::where('type','educational')->get();
        return response()->json($books,200);

    }
    public function show_entertainment()
    {
        $books = library::where('type','entertainment')->get();
        return response()->json($books,200);

    }

    public function show_all_books(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'type' =>'required|string',
        ]);

        $request->validate([
            'type' => 'required|in:educational,entertainment',
        ]);
       
        if($validator->fails())
        {
            return response()->json($validator->errors());
        }

        if($request->type=='educational'){
            $books = library::where('type','educational')->get();
        }
        if($request->type=='entertainment'){
            $books = library::where('type','entertainment')->get();
        }
        return response()->json($books,200);

    }

    public function add_to_favorite(Request $request){
        $id=$request->input('id');
        $book=library::find($id);
        if(!$book){
            return response('this book does not exist ,please try again',403);
        }
        $user_id = Auth::id();
        $student_id = DB::table('students')
        ->where('students.user_id',$user_id)
        ->value('students.id');

        $fav_book=DB::table('favorite_books')
        ->where('favorite_books.library_id',$id)
        ->where('favorite_books.student_id',$student_id)
        ->select('favorite_books.*')
        ->get();
        if(count($fav_book)!=0){
            return response('alredy in favorite',200);
        }

        $fav_book=favorite_book::create([
            'library_id' => $id,
            'student_id' => $student_id
        ]);
        return response($fav_book,200);
    }
    public function remove_from_favorite($id){
        $book=library::find($id);
        if(!$book){
            return response('this book does not exist ,please try again',404);
        }
        $user_id = Auth::id();
        $student_id = DB::table('students')
        ->where('students.user_id',$user_id)
        ->value('students.id');

        $fav_book=favorite_book::
        where('favorite_books.student_id',$student_id)
        ->where('favorite_books.library_id',$id)
        ->select('favorite_books.*')
        ->first();

        if(!$fav_book){
            return response('you can not delete this book');
        }

        $fav_book->delete();
        return response('the book deleted from favorite');

    }
    public function show_favorite_books(){
        $user_id = Auth::id();
        $student_id = DB::table('students')
        ->where('students.user_id',$user_id)
        ->value('students.id');

        $fav_books= DB::table('favorite_books')
        ->where('favorite_books.student_id',$student_id)
        ->join('libraries','favorite_books.library_id','=','libraries.id')
        ->SELECT('libraries.*')
        ->get();

        if(count($fav_books)==0){
            return response('there is no favorite books');
        }
        return response($fav_books,200);


    }

    public function delete_book($id){
        $book=library::find($id);

        if(!$book){
            return response('book not found');
        }

        $book->delete();
        return response('book deleted successfully');

    }

}
