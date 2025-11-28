<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\FlightSeat;
use Illuminate\Http\Request;

class FlightSeatController extends Controller
{
    // 1. LIHAT LAYOUT KURSI (Berdasarkan Flight ID)
    public function index($flight_id)
    {
        $flight = Flight::with('seats')->find($flight_id);

        if (!$flight) {
            return response()->json(['message' => 'Penerbangan tidak ditemukan'], 404);
        }

        return response()->json([
            'flight_number' => $flight->flight_number,
            'gate' => $flight->gate, // Menampilkan Gate
            'total_seats' => $flight->seats->count(),
            'seats' => $flight->seats
        ]);
    }

    // 2. GENERATE KURSI OTOMATIS
    public function store(Request $request, $flight_id)
    {
        $flight = Flight::find($flight_id);
        if (!$flight) return response()->json(['message' => 'Flight not found'], 404);

        // Validasi konfigurasi layout
        $request->validate([
            'class_type' => 'required|in:economy,business,first',
            'row_start' => 'required|integer|min:1', // Mulai baris ke berapa
            'row_end' => 'required|integer|gte:row_start', // Sampai baris ke berapa
            'columns' => 'required|string', // Contoh: "A,B,C,D,E,F"
        ]);

        $columns = explode(',', strtoupper($request->columns));
        $createdSeats = [];

        // Loop Baris (Row)
        for ($row = $request->row_start; $row <= $request->row_end; $row++) {
            // Loop Kolom (Seat Letter)
            foreach ($columns as $col) {
                $seatNumber = $row . $col; // Contoh: 12A

                // Cek biar gak duplikat
                $exists = FlightSeat::where('flight_id', $flight_id)
                    ->where('seat_number', $seatNumber)
                    ->exists();

                if (!$exists) {
                    $seat = FlightSeat::create([
                        'flight_id' => $flight_id,
                        'seat_number' => $seatNumber,
                        'class' => $request->class_type,
                        'is_available' => true
                    ]);
                    $createdSeats[] = $seat;
                }
            }
        }

        return response()->json([
            'message' => 'Berhasil generate ' . count($createdSeats) . ' kursi.',
            'data' => $createdSeats
        ], 201);
    }

    // 3. HAPUS SEMUA KURSI DI PENERBANGAN INI (Reset)
    public function destroy($flight_id)
    {
        FlightSeat::where('flight_id', $flight_id)->delete();
        return response()->json(['message' => 'Semua kursi di penerbangan ini telah dihapus']);
    }
}
