<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\TeachersListController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\GradesController;
use Database\Seeders\Class_subject;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use PHPUnit\Event\Code\Test;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//Auth Api's



Route::get('/symlink', function () {
    Artisan::call('storage:link');
});
Route::group(["middleware" => "translate"], function () {

    Route::post('registerStudent', [AuthController::class, 'AddAccountStudent']);
    Route::post('registerteacher', [AuthController::class, 'AddAccountTeacher']);
    Route::post('registerAdmin', [AuthController::class, 'AddAccounAdmin']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('loginAdmin', [AuthController::class, 'loginAdmin']);
    Route::post('generate_code', [AuthController::class, 'generate_code']);
    Route::delete('delete_student_account/{id}', [AuthController::class, 'delete_student_account']);
    Route::delete('delete_teacher_account/{id}', [AuthController::class, 'delete_teacher_account']);
    Route::post('edit_profile_student_for_admin', [AuthController::class, 'edit_profile_student_for_admin']);
    Route::post('edit_profile_teacher_for_admin', [AuthController::class, 'edit_profile_teacher_for_admin']);

    Route::post('passwordforget', [AuthController::class, 'userforgetpassword']);
    Route::post('checkcodepassword', [AuthController::class, 'usercheckcode']);
    Route::post('resetpassword', [AuthController::class, 'userresetpassword']);
    Route::get('profileteacher/{id}', [AuthController::class, 'profileteacher']);
    Route::get('profilestudent/{id}', [AuthController::class, 'profileStudent']);
    Route::post('add_uid_to_user', [AuthController::class, 'add_uid_to_user']);
    Route::post('add_fcm_to_user', [AuthController::class, 'add_fcm_to_user']);
    Route::post('get_data_from_uid', [AuthController::class, 'get_data_from_uid']);

});
//----------

Route::group(["middleware" => ["auth:api", "translate"]], function () {

    Route::get('logout', [AuthController::class, 'logout']);
    Route::post('edit_email', [AuthController::class, 'edit_email']);
    Route::post('edit_phone', [AuthController::class, 'edit_phone']);
    Route::post('edit_address', [AuthController::class, 'edit_address']);
    Route::post('edit_profile_picture', [AuthController::class, 'edit_profile_picture']);
    Route::post('edit_password', [AuthController::class, 'edit_password']);

    Route::post('EditprofileAdmin', [AuthController::class, 'EditprofileAdmin'])->middleware('admin');
    Route::get('profileAdmin', [AuthController::class, 'profileAdmin'])->Middleware('admin');
});



//advertisements api's

Route::group(["middleware" => "translate"], function () {

    Route::post('StoreAdvertisements', [AdvertisementController::class, 'store']);
    Route::get('Advertisements', [AdvertisementController::class, 'index']);
    Route::post('Advertisement', [AdvertisementController::class, 'show']);
    Route::post('show_all_by_class_by_type_for_admin', [AdvertisementController::class, 'show_all_by_class_by_type_for_admin']);
    Route::delete('destroy/{id}', [AdvertisementController::class, 'destroy']);
});

Route::group(["middleware" => ["auth:api", "translate"]], function () {
   
    Route::post('show_all_by_class_by_type', [AdvertisementController::class, 'show_all_by_class_by_type']);
    Route::get('show_all_by_class', [AdvertisementController::class, 'show_all_by_class']);
});


//Book api's
Route::group(["middleware" => "translate"], function () {
    Route::post('store_book', [LibraryController::class, 'store']);
    Route::delete('delete_book/{id}', [LibraryController::class, 'delete_book']);
    
    Route::get('show_educational_book', [LibraryController::class, 'show_educational']);
    Route::get('show_entertainment_book', [LibraryController::class, 'show_entertainment']);
    Route::post('show_all_books', [LibraryController::class, 'show_all_books']);


});

//teacher list Api's
Route::group(["middleware" => "translate"], function () {

    Route::post('show_teachers_by_class', [TeachersListController::class, 'show_teachers_by_class']);
    Route::get('get_all_teacher', [TeachersListController::class, 'get_all_teacher']);

});

//favorite api's
Route::group(["middleware" => ["auth:api", "translate"]], function () {
    Route::post('add_to_fav', [LibraryController::class, 'add_to_favorite']);
    Route::get('show_fav_books', [LibraryController::class, 'show_favorite_books']);
    Route::delete('remove_from_fav/{id}', [LibraryController::class, 'remove_from_favorite']);
    Route::post('add_to_favorite', [TeachersListController::class, 'add_to_favorite']);
    Route::delete('remove_from_favorite/{id}', [TeachersListController::class, 'remove_from_favorite']);
    Route::get('show_favorite_teachers', [TeachersListController::class, 'show_favorite_teachers']);
});

//class and subject api's

Route::group(["middleware" => "translate"], function () {

    Route::get('show_all_class_levels', [ClassController::class, 'show_all_class_levels']);
    Route::get('show_all_classes', [ClassController::class, 'show_all_classes']);
    Route::post('show_all_class_numbers', [ClassController::class, 'show_all_class_numbers']);
    
    Route::post('store_class_subject', [SubjectsController::class, 'store_class_subject']);
    Route::post('edit_subject', [SubjectsController::class, 'edit_subject']);
    Route::post('edit_class_subject', [SubjectsController::class, 'edit_class_subject']);

    Route::delete('delete_subject/{id}', [SubjectsController::class, 'delete_subject']);
    Route::delete('delete_class/{id}', [ClassController::class, 'delete_class']);
    Route::post('show_subjects_of_the_class', [SubjectsController::class, 'show_subjects_of_the_class']);  
    Route::get('show_all_subjects', [SubjectsController::class, 'show_all_subjects']);  
    Route::post('show_subject', [SubjectsController::class, 'show_subject']);
    Route::post('store', [ClassController::class, 'store']);
    Route::post('store_subject', [SubjectsController::class, 'store_subject']);
    Route::post('store_photo_about_subject', [SubjectsController::class, 'store_photo_about_subject']);
    Route::post('store_subject_units', [SubjectsController::class, 'store_subject_units']);
    Route::post('EditClass/{id}', [ClassController::class, 'EditClass']);

});

Route::group(["middleware" => ["auth:api", "translate"]], function () {
        
    //Route::get('showStudentsByClass/{id}', [ClassController::class, 'showStudentsByClass']);
   
});

//test api's

Route::group(["middleware" => "translate"], function () {
    Route::post('store_test', [TestController::class, 'store_test']);
    Route::post('show_test_by_class_level', [TestController::class, 'show_test_by_class_level']);
    Route::delete('delete_test/{id}', [TestController::class, 'delete_test']);
    Route::get('show', [TestController::class, 'index']);
});


//task api's
Route::group(["middleware"=>"translate"],function() {

    Route::post('show_question',[TaskController::class,'show_question']);

});

Route::group(["middleware" => ["auth:api", "translate"]], function () {

    Route::post('solve_task', [TaskController::class, 'solve_task']);
    Route::get('show_task/{id}', [TaskController::class, 'show_task']);
    Route::get('show_task_for_teacher/{id}', [TaskController::class, 'show_task_for_teacher']);
    Route::get('show_all_tasks_for_student', [TaskController::class, 'show_all_tasks_for_student']);
    Route::get('show_all_tasks_for_teacher', [TaskController::class, 'show_all_tasks_for_teacher']);
    Route::post('store_task', [TaskController::class, 'store_task']);
    Route::post('lock_task', [TaskController::class, 'lock_task']);
    Route::post('store_question', [TaskController::class, 'store_question']);
    Route::get('show_classes_for_teacher_for_joud', [TaskController::class, 'show_classes_for_teacher_for_joud']);
    Route::delete('delete_question/{id}',[TaskController::class,'delete_question']);

});

//wallet

Route::group(["middleware"=>"translate"],function() {

    Route::post('create_fee', [WalletController::class, 'create_fee']);
    Route::post('deposit_wallet', [WalletController::class, 'deposit_wallet']);
    Route::get('all_wallet_balance', [WalletController::class, 'all_wallet_balance']);
    Route::post('show_wallet_details_for_admin', [WalletController::class, 'show_wallet_details_for_admin']);

});

Route::group(["middleware" => ["auth:api", "translate"]], function () {

    Route::post('paid_fee', [WalletController::class, 'paid_fees']);
    Route::get('show', [WalletController::class, 'show']);
    Route::get('show_fees', [WalletController::class, 'show_fees']);

});

//activity
Route::group(["middleware" => ["auth:api", "translate"]], function () {
    Route::get('activity', [ActivityController::class, 'activity']);
});


//schedule
Route::group(["middleware" => ["auth:api", "translate"]], function () {
    Route::get('show_the_schedule_for_student', [SubjectsController::class, 'show_the_schedule_for_student']);
    Route::get('show_the_schedule_for_teacher', [SubjectsController::class, 'show_the_schedule_for_teacher']);
});


//grades

Route::group(["middleware"=>"translate"],function() {

    Route::post('store_grade_test', [GradesController::class, 'store_grade_test']);
    Route::post('delete_grade', [GradesController::class, 'delete_grade']);
    Route::post('show_student_grade_for_admin', [GradesController::class, 'show_student_grade_for_admin']);
    
});

Route::group(["middleware" => ["auth:api", "translate"]], function () {

    Route::post('show_grade_by_type', [GradesController::class, 'show_grade_by_type']);
    Route::get('show_the_total_grade', [GradesController::class, 'show_the_total_grade']);
    Route::get('rank', [GradesController::class, 'rank']);
});

//show students for admin

Route::get('number_of_total_school_students_for_admin', [StudentController::class, 'number_of_total_school_students_for_admin']);

Route::group(["middleware"=>"translate"],function() {

    Route::post('show_students_in_class', [StudentController::class, 'show_students_in_class']);
    Route::post('show_student_profile', [StudentController::class, 'show_student_profile']);
    
});

//show teachers for admin
Route::group(["middleware"=>"translate"],function() {

    Route::post('show_all_teachers', [TeacherController::class, 'show_all_teachers']);
    Route::get('show_list_of_all_teachers_for_admin', [TeacherController::class, 'show_list_of_all_teachers_for_admin']);
    
});

//classes and student for teacher
Route::group(["middleware" => ["auth:api", "translate"]], function () {

    Route::get('show_classes_for_teacher', [TeacherController::class, 'show_classes_for_teacher']);
    Route::post('show_students_by_class_for_teacher', [TeacherController::class, 'show_students_by_class_for_teacher']);

});

//search
Route::group(["middleware"=>"translate"],function() {

    Route::post('search_for_student', [TeacherController::class, 'search_for_student']);
    
});