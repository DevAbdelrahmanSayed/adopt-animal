<?php

namespace Modules\Like\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Like\Database\factories\LikeFactory;

class Like extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): LikeFactory
    {
        //return LikeFactory::new();
    }
}
