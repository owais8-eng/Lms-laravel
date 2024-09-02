<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;
    protected $fillable = [
        'fee_name',
        'benefits',
        'type',
        'due_date'
    ];

    public static $rules = [
        'type' => 'required|in:bus,school,other',
    ];
    
    public function aboutwallet(){
        return $this->hasMany(about_wallet::class);
    }



}
