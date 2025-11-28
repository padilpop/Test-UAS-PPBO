<?php

namespace App\Models;

use App\Interfaces\Ticketable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model implements Ticketable
{
    use HasFactory;

    // Memberi tahu model ini konek ke tabel 'trains'
    protected $table = 'trains';

    protected $fillable = [
        'train_name',
        'origin_station',
        'destination_station',
        'departure_time',
        'base_price',
    ];

    // Ini untuk memastikan format datanya benar
    protected $casts = [
        'departure_time' => 'datetime',
        'base_price' => 'integer',
    ];

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
        $seat = FlightSeat::find($seatId);
        if ($seat) {
            $seat->update(['is_available' => false]);
        }
    }

    // 1. Relasi ke Gerbong
    public function wagons()
    {
        return $this->hasMany(Wagon::class);
    }
    public function seats()
    {
        return $this->hasManyThrough(TrainSeat::class, Wagon::class);
    }

    // UPDATE Interface Ticketable (Polymorphism)
    // app/Models/Train.php

    public function bookSeat($seatId)
    {
        $affectedRows = TrainSeat::where('id', $seatId)
            ->where('is_available', true)
            ->update(['is_available' => false]);

        if ($affectedRows === 0) {
            throw new \Exception("Maaf, kursi gerbong ini baru saja dipesan orang lain.");
        }

        return TrainSeat::find($seatId)->seat_number;
    }

    // Relasi Polymorphic (Satu pesawat bisa punya banyak booking)
    public function bookings()
    {
        return $this->morphMany(Booking::class, 'ticketable');
    }
}
