<?php

namespace Modules\User\app\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Modules\Post\app\Models\Post;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name_',
        'username',
        'email',
        'profile',
        'password',
        'contact_number',
        'country',
        'address',
        'otp_code',
        'otp_expires_at'
    ];

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
        return $this->hasMany(Post::class, 'user_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Post::class, 'favorites', 'user_id', 'post_id');
    }

    public function otp()
    {
        return $this->hasOne(__CLASS__, 'id');
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
