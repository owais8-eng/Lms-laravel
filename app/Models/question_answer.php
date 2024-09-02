<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\task_question;


class question_answer extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'task_question_id',
        'the_answer',
        'correct_answer'

    ];
    public function task_question(){
        return $this->belongsTo(task_question::class);
    }
}
