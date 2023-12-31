<?php

namespace Modules\User\app\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Modules\Post\app\Models\Post;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;


    protected $fillable = ['name_','username','email','password','contact_number','country','address'];


    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function posts()
    {
        return $this->hasMany(Post::class,'user_id');
    }
    public function favorites()
    {
        return $this->belongsToMany(Post::class, 'favorites');
    }


    public function getJWTCustomClaims()
    {
        return [];
    }
}
