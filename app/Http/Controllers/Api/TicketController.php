<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class TicketController extends Controller
{
    public function show($bookingCode)
    {
        // // Eager Loading: Ambil Booking + Detail Tiket + Info Pesawat/Keretanya
        // $booking = Booking::with(['tickets', 'ticketable'])
        //     ->where('booking_code', $bookingCode)
        //     ->first();

        // Baru: Eager Loading: Ambil Booking + Detail Tiket + Info Pesawat/Keretanya + User
        $booking = Booking::with(['user', 'tickets', 'ticketable']) // ⬅️ TAMBAHKAN 'user'
            ->where('booking_code', $bookingCode)
            ->first();

        // Validasi
        if (!$booking) {
            return response()->json(['message' => 'Tiket tidak ditemukan'], 404);
        }

        // // Pastikan hanya pemilik tiket yang bisa lihat (Keamanan)
        // if ($booking->user_id !== request()->user()->id) {
        //     return response()->json(['message' => 'Anda tidak berhak melihat tiket ini'], 403);
        // }

        // Baru: Otorisasi & Pengecekan Status Pembayaran
        $loggedInUser = request()->user();
        
        // Baru: Pastikan hanya pemilik tiket / Admin yang boleh melihat
        if ($loggedInUser->role !== 'admin' && $booking->user_id !== $loggedInUser->id) {
            return response()->json(['message' => 'Akses ditolak. Anda tidak berhak melihat tiket ini.'], 403);
        }

        // Cek status pembayaran (Wajib Tiket Terbit)
        if ($booking->payment_status !== 'paid') {
            return response()->json([
                'message' => 'Tiket belum terbit. Status pembayaran: ' . $booking->payment_status
            ], 400);
        }

        // Format Response JSON Cantik
        return response()->json([
            'status' => 'success',
            'data' => [
                'booking_info' => [
                    'code' => $booking->booking_code,
                    'status' => $booking->payment_status, // paid/unpaid
                    'total_price' => (int) $booking->total_price,
                    'transaction_date' => $booking->created_at->format('d M Y H:i'),

                    // Baru: Info Kontak Pemesan
                    'contact_person' => [ 
                        'full_name' => $booking->user->full_name,
                        'email' => $booking->user->email,
                        'phone_number' => $booking->user->phone_number,
                    ]
                ],
                'trip_info' => $booking->ticketable, // Data Flight atau Train otomatis muncul di sini
                'passengers' => $booking->tickets->map(function ($ticket) {
                    return [
                        'ticket_number' => $ticket->ticket_code,
                        'passenger_name' => $ticket->passenger_name,
                        'seat_number' => $ticket->seat_assigned,
                        'qr_code_data' => $ticket->qr_string ?? 'QR-DUMMY-' . $ticket->ticket_code
                    ];
                })
            ]
        ]);
    }
}
