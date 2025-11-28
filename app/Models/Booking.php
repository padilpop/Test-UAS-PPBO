<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $primaryKey = 'booking_id';
    protected $guarded = [];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Polymorphic
    public function ticketable()
    {
        return $this->morphTo();
    }

    // Relasi ke detail tiket penumpang
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'booking_id');
    }
}
