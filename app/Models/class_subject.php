<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\subject;
use App\Models\classs;
use App\Models\teacher;
use App\Models\task;
use App\Models\test;


class class_subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'teacher_id',
        'class_id',
        'subject_id',
        'time_on_sun',
        'time_on_mon',
        'time_on_tue',
        'time_on_wed',
        'time_on_thu',
        'exam_date_and_time'
    ];
    public static $rules = [
        'time_on_sun'=>'required|in:1,2,3,4,5,6,7',
        'time_on_mon'=>'required|in:1,2,3,4,5,6,7',
        'time_on_tue'=>'required|in:1,2,3,4,5,6,7',
        'time_on_wed'=>'required|in:1,2,3,4,5,6,7',
        'time_on_thu'=>'required|in:1,2,3,4,5,6,7',
    ];

    public function task(){
        return $this->hasMany(task::class);
    }
    public function test(){
        return $this->hasMany(test::class);
    }
    public function teacher(){
        return $this->belongsTo(teacher::class);
    }
    public function subject(){
        return $this->belongsTo(subject::class);
    }
    public function class(){
        return $this->belongsTo(classs::class);
    }
}
