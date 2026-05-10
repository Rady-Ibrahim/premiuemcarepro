<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ticket extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(TicketDetail::class, 'ticket_id');
    }

    public function scopeForCurrentUser($query, $customerId = null)
    {
        $user = Auth::user();
        return $query->where('user_id', $customerId ?? $user->id);
    }

    public function scopeOrdered($query, $order = 'desc', $column = 'created_at')
    {
        return $query->orderBy($column, $order);
    }
}
