<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'booking_id',
        'seat_id',
        'ticket_code',
        'passenger_name',
        'passenger_id_card',
        'seat_assigned',
        'eticket_url',
        'qr_string',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
