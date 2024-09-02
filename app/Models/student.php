<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\user;
use App\Models\favorite_teacher;
use App\Models\exam_grade;
use App\Models\task_grade;
use App\Models\about_wallet;
use App\Models\classs;
use App\Models\favorite_book;




class student extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'class_id',
        'wallet_balance',
        'remain',
        'bus',
        'enrollment_date',
        'parent_name',
        'parent_phone',
        'parent_email'
    ];
    public function user(){
        return $this->belongsTo(user::class);
    }
    public function class(){
        return $this->belongsTo(classs::class);
    }
    public function favorite_teacher(){
        return $this->hasMany(favorite_teacher::class);
    }
    public function about_wallet(){
        return $this->hasMany(about_wallet::class);
    }
    public function grade(){
        return $this->hasMany(grade::class);
    }
    public function task_grade(){
        return $this->hasMany(task_grade::class);
    }
    public function favorite_book(){
        return $this->hasMany(favorite_book::class);
    }
    public function fee()  {
        return $this->belongsTo(Fee::class);
    }
}
