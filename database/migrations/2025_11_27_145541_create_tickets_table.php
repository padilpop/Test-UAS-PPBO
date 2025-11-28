<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('ticket_id');
            // Hubungkan ke booking
            $table->foreignId('booking_id')->constrained('bookings', 'booking_id')->onDelete('cascade');

            // Opsional: Seat ID (bisa null kalau belum pilih kursi)
            $table->unsignedBigInteger('seat_id')->nullable();

            $table->string('ticket_code', 20)->unique();
            $table->string('passenger_name');
            $table->string('passenger_id_card')->nullable();
            $table->string('seat_assigned', 10)->nullable();
            $table->string('eticket_url')->nullable();
            $table->string('qr_string')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
