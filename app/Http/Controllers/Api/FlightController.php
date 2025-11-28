<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight; // Pastikan Model Flight dipanggil
use Illuminate\Http\Request;

//Ini Class FlightController

class FlightController extends Controller
{
    // 1. TAMPILKAN SEMUA DATA
    public function index()
    {
        $flights = Flight::with(['airline', 'originAirport', 'destinationAirport'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $flights
        ]);
    }

    // 2. SIMPAN DATA BARU
    public function store(Request $request)
    {
        $validated = $request->validate([
            'flight_number' => 'required|string|unique:flights,flight_number',
            'airline_id' => 'required|exists:airlines,id',
            'origin_airport_code' => 'required|exists:airports,airport_code',
            'destination_airport_code' => 'required|exists:airports,airport_code|different:origin_airport_code',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'price' => 'required|numeric|min:0',
        ]);

        $flight = Flight::create($validated);

        return response()->json([
            'message' => 'Penerbangan berhasil dibuat',
            'data' => $flight
        ], 201);
    }

    // 3. LIHAT DETAIL SATU PENERBANGAN
    // GET: Detail satu penerbangan (Lengkap dengan info ketersediaan kursi)
    public function show($id)
    {
        $flight = Flight::with(['airline', 'originAirport', 'destinationAirport'])

            ->withCount(['seats as total_seats'])

            ->withCount(['seats as available_seats' => function ($query) {
                $query->where('is_available', true);
            }])
            ->find($id);

        if (!$flight) {
            return response()->json(['message' => 'Penerbangan tidak ditemukan'], 404);
        }

        // Hitung durasi penerbangan
        $departure = \Carbon\Carbon::parse($flight->departure_time);
        $arrival = \Carbon\Carbon::parse($flight->arrival_time);
        $duration = $departure->diffForHumans($arrival, true);

        return response()->json([
            'message' => 'Detail penerbangan ditemukan',
            'data' => [
                'id' => $flight->id,
                'flight_number' => $flight->flight_number,
                'gate' => $flight->gate ?? 'TBA', // Tampilkan Gate (atau TBA jika null)
                'price' => $flight->price,
                'times' => [
                    'departure' => $departure->format('Y-m-d H:i'),
                    'arrival' => $arrival->format('Y-m-d H:i'),
                    'duration' => $duration,
                ],
                'route' => [
                    'origin' => $flight->originAirport->city . ' (' . $flight->originAirport->airport_code . ')',
                    'destination' => $flight->destinationAirport->city . ' (' . $flight->destinationAirport->airport_code . ')',
                ],
                'airline' => [
                    'name' => $flight->airline->name,
                    'logo' => $flight->airline->logo_url,
                ],
                'inventory' => [
                    'total_seats' => $flight->total_seats,
                    'available_seats' => $flight->available_seats,
                    'is_full' => $flight->available_seats == 0,
                ],
            ]
        ]);
    }

    // 4. UPDATE DATA
    public function update(Request $request, $id)
    {
        $flight = Flight::find($id);
        if (!$flight) return response()->json(['message' => 'Not Found'], 404);

        $flight->update($request->all());

        return response()->json(['message' => 'Update berhasil', 'data' => $flight]);
    }

    // 5. HAPUS DATA
    public function destroy($id)
    {
        $flight = Flight::find($id);
        if (!$flight) return response()->json(['message' => 'Not Found'], 404);

        $flight->delete();
        return response()->json(['message' => 'Data dihapus']);
    }
}
