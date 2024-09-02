<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\task;
use App\Models\question_answer;


class task_question extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'task_id',
        'the_question',
        'question_grade'
    ];
    public function task(){
        return $this->belongsTo(task::class);
    }
    public function question_answer(){
        return $this->hasMany(question_answer::class);
    }
}
