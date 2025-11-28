<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Ticketable;

class Flight extends Model implements Ticketable
{
    use HasFactory;

    // Biar bisa diisi semua kolomnya
    protected $guarded = ['id'];

    // Relasi: Penerbangan ini milik Maskapai siapa?
    public function airline()
    {
        return $this->belongsTo(Airline::class, 'airline_id');
    }

    // Relasi: Berangkat dari bandara mana?
    public function originAirport()
    {
        return $this->belongsTo(Airport::class, 'origin_airport_code', 'airport_code');
    }

    // Relasi: Tujuannya ke bandara mana?
    public function destinationAirport()
    {
        return $this->belongsTo(Airport::class, 'destination_airport_code', 'airport_code');
    }

    // Relasi ke Kursi
    public function seats()
    {
        return $this->hasMany(FlightSeat::class, 'flight_id');
    }

    // Implementasi Interface
    public function getBasePrice()
    {
        return $this->base_price;
    }

    public function getCode()
    {
        return $this->flight_number;
    }

    public function decrementSeat($seatId)
    {
        // Logika update kursi pesawat jadi unavailable
        $seat = FlightSeat::find($seatId);
        if ($seat) {
            $seat->update(['is_available' => false]);
        }
    }


    public function bookSeat($seatId)
    {
        $affectedRows = FlightSeat::where('id', $seatId)
            ->where('is_available', true)
            ->update(['is_available' => false]);

        if ($affectedRows === 0) {
            throw new \Exception("Maaf, kursi ini baru saja dipesan orang lain.");
        }

        // Ambil data kursi untuk direturn nomornya
        return FlightSeat::find($seatId)->seat_number;
    }

    // Relasi Polymorphic (Satu pesawat bisa punya banyak booking)
    public function bookings()
    {
        return $this->morphMany(Booking::class, 'ticketable');
    }
}
