<?php

namespace Modules\Favorite\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Favorite\Database\factories\FavoriteFactory;

class Favorite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): FavoriteFactory
    {
        //return FavoriteFactory::new();
    }
}
