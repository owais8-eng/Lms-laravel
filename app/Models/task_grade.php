<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\student;
use App\Models\task;


class task_grade extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'student_id',
        'task_id',
        'grade'
    ];
    public function task(){
        return $this->belongsTo(task::class);
    }
    
    public function student(){
        return $this->belongsTo(student::class);
    }
}
