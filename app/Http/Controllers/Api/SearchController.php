<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\Train;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // 1. Validasi Input JSON
        $request->validate([
            'from' => 'required|string',
            'to'   => 'required|string',
            'date' => 'required|date',
            'type' => 'nullable|in:flight,train,all'
        ]);

        $results = [];

        $searchType = $request->input('type', 'all');

        // 2. Cari PESAWAT
        if ($searchType === 'flight' || $searchType === 'all') {
            $flights = Flight::with('airline')
                ->where('origin_airport_code', $request->from)
                ->where('destination_airport_code', $request->to)
                ->whereDate('departure_time', $request->date)
                ->get()
                ->map(function ($item) {
                    $item->type = 'flight';
                    return $item;
                });

            $results = array_merge($results, $flights->toArray());
        }

        // 3. Cari KERETA
        if ($searchType === 'train' || $searchType === 'all') {
            $trains = Train::where('origin_station_code', $request->from)
                ->where('dest_station_code', $request->to)
                ->whereDate('departure_time', $request->date)
                ->get()
                ->map(function ($item) {
                    $item->type = 'train';
                    return $item;
                });

            $results = array_merge($results, $trains->toArray());
        }

        // 4. Return Hasil
        if (empty($results)) {
            return response()->json([
                'message' => 'Maaf, tidak ada jadwal yang ditemukan untuk rute tersebut.',
                'meta' => [
                    'origin' => $request->from,
                    'destination' => $request->to,
                    'date' => $request->date
                ],
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Pencarian berhasil',
            'meta' => [
                'origin' => $request->from,
                'destination' => $request->to,
                'date' => $request->date,
                'total_found' => count($results)
            ],
            'data' => $results
        ]);
    }
}
