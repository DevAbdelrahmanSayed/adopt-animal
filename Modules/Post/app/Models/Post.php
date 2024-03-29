<?php

namespace Modules\Post\app\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\app\Models\Category;
use Modules\User\app\Models\User;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'pet_photo',
        'pet_type',
        'pet_desc',
        'pet_name',
        'pet_gender',
        'pet_age',
        'pet_breed',
    ];

    protected $casts = [
        'pet_photo' => 'json',
    ];

    public function setPhotosAttribute($value)
    {
        $this->attributes['pet_photo'] = json_encode($value);
    }

    public function getPhotosAttribute($value)
    {
        return json_decode($value, true);
    }

    protected $searchable = [
        'pet_breed',
        'pet_type',
        'pet_name',
        'pet_gender',
        'pet_age',
        'pet_desc',

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'post_id', 'user_id');
    }

    public function isFavoritedByUser()
    {
        $userId = auth()->id();

        return $this->favoritedBy()->where('user_id', $userId)->exists();
    }

    public function scopeSearch($query, $texts)
    {
        $textArray = explode(' ', $texts);
        foreach ($textArray as $text) {
            foreach ($this->searchable as $column) {
                $query->orWhere($column, 'like', '%'.$text.'%');
            }
        }

        return $query;
    }

    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }
}
