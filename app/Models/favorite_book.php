<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\library;
use App\Models\student;

class favorite_book extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'library_id',
        'student_id'
    ];
    public function library(){
        return $this->belongsTo(library::class);
    }
    public function student(){
        return $this->belongsTo(student::class);
    }

}
