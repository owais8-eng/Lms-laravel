<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\student;
use App\Models\advertisement;
use App\Models\class_subject;


class classs extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'class_level',
        'class_number',
        'number_of_students'
    ];
    public function advertisement (){
        return $this->hasMany(advertisement::class);
    }
    public function student(){
        return $this->hasMany(student::class);
    }
    public function class_subject(){
        return $this->hasMany(class_subject::class);
    }
    public function incrementStudentCount()
    {
        $this->increment('number_of_students');

    }
}
