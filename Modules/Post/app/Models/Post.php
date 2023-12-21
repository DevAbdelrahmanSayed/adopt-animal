<?php

namespace Modules\Post\app\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'photo',
        'pet_type',
        'pet_name',
        'pet_color',
        'pet_age',
        'pet_breed',
        'contact_number'
    ];

    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }
}
