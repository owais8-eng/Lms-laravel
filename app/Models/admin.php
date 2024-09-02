<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\user;

class admin extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id'
    ];
    public function user(){
        return $this->belongsTo(user::class);
    }

}
