<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\user;
use App\Models\class_subject;
use App\Models\favorite_teacher;
use App\Models\description_about_the_teacher;


class teacher extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'rate',
        'hire_date',
        'specialization',
        'education',
        'salary',
        'about',
        'class_level',
        'cv'

    ];

    public static $rules = [
        'class_level' => 'required|in:7,8,9',
    ];
    public function user(){
        return $this->belongsTo(user::class);
    }
    public function class_subject(){
        return $this->hasMany(class_subject::class);
    }
    public function favorite_teacher(){
        return $this->hasMany(favorite_teacher::class);
    }
   
}
