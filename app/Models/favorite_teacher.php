<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\teacher;
use App\Models\student;


class favorite_teacher extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'teacher_id',
        'student_id'
    ];
    public function teacher(){
        return $this->belongsTo(teacher::class);
    }
    public function student(){
        return $this->belongsTo(student::class);
    }

}
