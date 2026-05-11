<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $with = ['lastMessage'];

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'desc');
    }

    public function lastMessage()
    {
        return $this->belongsTo(Message::class, 'last_message_id');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user1_id', $userId)
              ->orWhere('user2_id', $userId);
        });
    }

    public function scopeWithLatestMessage($query)
    {
        return $query->with('lastMessage.sender');
    }

    public function getOtherUser($userId)
    {
        return $this->user1_id === $userId ? $this->user2 : $this->user1;
    }

    public function isPartOf($userId)
    {
        return $this->user1_id === $userId || $this->user2_id === $userId;
    }
}
