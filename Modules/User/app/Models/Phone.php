<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Database\factories\PhoneFactory;

class Phone extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'dial_code', 'iso_code'];

    protected static function newFactory(): PhoneFactory
    {
        //return PhoneFactory::new();
    }
}
