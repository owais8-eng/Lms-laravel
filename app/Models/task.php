<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\class_subject;
use App\Models\task_question;
use App\Models\task_grade;


class task extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'class_subject_id',
        'finished',
        'total_grade'
    ];
    public function class_subject(){
        return $this->belongsTo(class_subject::class);
    }
    public function task_question(){
        return $this->hasMany(task_question::class);
    }
    public function task_grade(){
        return $this->hasMany(task_grade::class);
    }
}
