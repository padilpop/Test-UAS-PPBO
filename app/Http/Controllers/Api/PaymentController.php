<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|exists:bookings,booking_code'
        ]);

        // Cari booking milik user yang sedang login
        $booking = Booking::where('booking_code', $request->booking_code)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking tidak ditemukan atau bukan milik Anda.'], 404);
        }

        if ($booking->payment_status === 'paid') {
            return response()->json(['message' => 'Booking ini sudah dibayar sebelumnya.'], 400);
        }

        // Simulasi Update Status jadi PAID
        $booking->update([
            'payment_status' => 'paid',
            'updated_at' => now()
        ]);

        return response()->json([
            'message' => 'Pembayaran berhasil! Tiket telah terbit.',
            'data' => [
                'booking_code' => $booking->booking_code,
                'status' => 'paid',
                'paid_at' => $booking->updated_at->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
