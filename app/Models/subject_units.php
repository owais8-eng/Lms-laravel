<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\subject;


class subject_units extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'subject_id',
        'unit_number',
        'title',
        'description',
        'photo_path'
    ];
    public function subject(){
        return $this->belongsTo(subject::class);
    }
}
