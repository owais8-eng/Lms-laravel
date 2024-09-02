<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verficationcode extends Model
{
    use HasFactory;
    protected $table = 'verify_codes';
    protected $fillable = [
        'user_id',
        'email',
        'password',
        'code',
        'created_at',
    ];
    public function verfiycodeUser()
    {
        return $this->hasOne(User::class);
    }
}
