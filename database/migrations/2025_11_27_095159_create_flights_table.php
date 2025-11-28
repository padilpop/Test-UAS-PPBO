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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();

            // 1. Relasi ke Tabel Maskapai (Airline)
            // Pastikan tabel 'airlines' sudah ada sebelumnya
            $table->foreignId('airline_id')->constrained('airlines')->onDelete('cascade');

            // 2. Relasi ke Tabel Bandara (Airport) - Asal & Tujuan
            // Kita pakai string karena Primary Key di tabel airports adalah 'airport_code' (contoh: CGK), bukan ID angka.
            $table->string('origin_airport_code', 3);
            $table->string('destination_airport_code', 3);

            // Definisikan Foreign Key secara manual untuk bandara
            $table->foreign('origin_airport_code')->references('airport_code')->on('airports')->onDelete('cascade');
            $table->foreign('destination_airport_code')->references('airport_code')->on('airports')->onDelete('cascade');

            // 3. Data Detail Penerbangan
            $table->string('flight_number', 10)->unique();
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');
            $table->decimal('price', 15, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
