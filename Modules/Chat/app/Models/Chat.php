<?php

namespace Modules\Chat\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Chat\Database\factories\ChatFactory;
use Modules\User\app\Models\User;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * The attributes that are mass assignable.
     */
    protected static function newFactory()
    {
        //return ChatFactory::new();
    }
}
