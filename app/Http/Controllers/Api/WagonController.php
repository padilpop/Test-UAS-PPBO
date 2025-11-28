<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Train;
use App\Models\Wagon;
use App\Models\TrainSeat;
use Illuminate\Support\Facades\DB;

class WagonController extends Controller
{
    // Function untuk menambah gerbong ke kereta tertentu
    public function store(Request $request)
    {
        $request->validate([
            'train_id' => 'required|exists:trains,id',
            'name' => 'required|string',
            'class' => 'required|in:economy,business,executive,luxury',
            'seat_capacity' => 'required|integer|min:1|max:100'
        ]);

        try {
            DB::beginTransaction();

            // 1. Buat Gerbong
            $wagon = Wagon::create([
                'train_id' => $request->train_id,
                'name' => $request->name,
                'class' => $request->class
            ]);

            // 2. Generate Kursi Otomatis (1A, 1B, 2A, 2B...)
            $seats = [];
            $cols = ['A', 'B', 'C', 'D']; // Konfigurasi 2-2

            for ($i = 1; $i <= $request->seat_capacity; $i++) {

                $rowIndex = ceil($i / 4);
                $colIndex = ($i - 1) % 4;

                $seatNumber = $rowIndex . $cols[$colIndex];

                $seats[] = [
                    'wagon_id' => $wagon->id,
                    'seat_number' => $seatNumber,
                    'is_available' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Masukkan semua kursi sekaligus (Bulk Insert biar cepat)
            TrainSeat::insert($seats);

            DB::commit();

            return response()->json([
                'message' => 'Gerbong dan ' . count($seats) . ' kursi berhasil dibuat',
                'data' => $wagon->load('seats') // Tampilkan gerbong beserta kursinya
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal membuat gerbong: ' . $e->getMessage()], 500);
        }
    }
}
