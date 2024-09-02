<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\classs;

class advertisement extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'class_id',
        'type',
        'photo_path'
    ];

    public static $rules = [
        'type' => 'required|in:bus,trips,wallet,exam,results,instuctions,other',

    ];
    public function class(){
        return $this->belongsTo(classs::class);
    }
}
