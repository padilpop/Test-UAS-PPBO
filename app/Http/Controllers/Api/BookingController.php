<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Flight;
use App\Models\Train;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'type' => 'required|in:flight,train',
            'id' => 'required|integer', // ID dari Flight atau Train
            'passengers' => 'required|array|min:1',
            'passengers.*.name' => 'required|string',
            'passengers.*.seat_id' => 'required|integer', // ID Kursi yang dipilih
        ]);

        // 2. Tentukan Model berdasarkan Tipe (Factory Pattern Sederhana)
        $entityClass = $request->type === 'flight' ? Flight::class : Train::class;
        $entity = $entityClass::find($request->id);

        if (!$entity) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        // 3. Hitung Harga Total
        // (Harga Dasar Entitas x Jumlah Penumpang)
        $totalPrice = $entity->getBasePrice() * count($request->passengers);

        try {
            DB::beginTransaction();

            // 4. Buat Record Booking (Header)
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'ticketable_id' => $entity->id,
                'ticketable_type' => $entityClass,
                'booking_code' => 'BOOK-' . Str::upper(Str::random(8)),
                'total_price' => $totalPrice,
                'payment_status' => 'unpaid',
            ]);

            // 5. Buat Detail Tiket per Penumpang & Update Kursi
            foreach ($request->passengers as $passenger) {
                $entity->decrementSeat($passenger['seat_id']);

                Ticket::create([
                    'booking_id' => $booking->booking_id,
                    'seat_id' => $passenger['seat_id'],
                    'ticket_code' => 'TKT-' . Str::upper(Str::random(10)),
                    'passenger_name' => $passenger['name'],
                    'seat_assigned' => 'XX',
                    'qr_string' => 'dummy-qr-string',
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Booking berhasil dibuat',
                'data' => $booking->load('tickets'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
