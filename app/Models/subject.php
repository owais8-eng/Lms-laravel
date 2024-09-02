<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\class_subject;
use App\Models\subject_units;
use App\Models\photos_about_subject;


class subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'the_class',
        'total_grade',
        'about_subject',
        'photo_path',
        'book_path'
    ];

    public static $rules = [
        'the_class' => 'required|in:7,8,9',
    ];

    public function class_subject(){
        return $this->hasMany(class_subject::class);
    }
   
    public function subject_unit(){
        return $this->hasMany(subject_units::class);
    }

}
