<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\student;


class about_wallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'student_id',
        'fee_id',
        'amount',
        'description'
    ];

    public static $rules = [
        'description' => 'required|in:deposit,withdraw',
    ];
    public function student(){
        return $this->belongsTo(student::class);
    }

    public function fee(){
        return $this->belongsTo(Fee::class);
    }
}
