<?php

namespace Modules\Category\app\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name'];

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }
}
