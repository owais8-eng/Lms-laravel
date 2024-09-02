<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\admin;
use App\Models\student;
use App\Models\teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;




class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'uid',
        'fcm_token',
        'role',
        'first_name',
        'last_name',
        'birthdate',
        'email',
        'password',
        'phone',
        'address',
        'profile_picture_path',
        'gender'
    ];
    public static $rules = [
        'role' => 'required|in:student,teacher,admin',
        'gender' => 'required|in:male,female',

    ];
    
    public function student(){
        return $this->hasOne(student::class);
    }
    public function teacher(){
        return $this->hasOne(teacher::class);
    }
    public function admin(){
        return $this->hasOne(admin::class);
    }
    public function verfiycode()
    {
        return $this->hasOne(Verficationcode::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    
   
}
