<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\student;
use App\Models\test;

class grade extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'student_id',
        'test_id',
        'grade'
    ];
    public function test(){
        return $this->belongsTo(test::class);
    }
    
    public function student(){
        return $this->belongsTo(student::class);
    }
}
