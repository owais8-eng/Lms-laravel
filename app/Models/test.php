<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\class_subject;
use App\Models\grade;


class test extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'class_subject_id',
        'type',
        'exam_paper_path'
    ];
    public static $rules = [
        'type' => 'required|in:exam,oral_exam,homework,quiz',
    ];
    public function class_subject(){
        return $this->belongsTo(class_subject::class);
    }
    
    public function grade(){
        return $this->hasMany(grade::class);
    }
}
