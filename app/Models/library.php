<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\favorite_book;


class library extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'book_name',
        'book_path',
        'photo_path',
        'type'
    ];
    public static $rules = [
        'type' => 'required|in:educational,entertainment',

    ];
    public function favorite_book(){
        return $this->hasMany(favorite_book::class);
    }
}
