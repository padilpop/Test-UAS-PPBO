<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightSeat extends Model
{
    use HasFactory;

    // Agar kita bisa melakukan mass assignment (Create banyak sekaligus)
    protected $guarded = ['id'];

    /**
     * Relasi: Sebuah kursi dimiliki oleh satu penerbangan (Flight).
     */
    public function flight()
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }
}