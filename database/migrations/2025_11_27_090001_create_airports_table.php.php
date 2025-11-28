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
        Schema::create('airports', function (Blueprint $table) {
            // Kita pakai Kode Bandara (misal: CGK, DPS) sebagai Primary Key
            // bukan ID angka biasa.
            $table->string('airport_code', 3)->primary();

            $table->string('airport_name');
            $table->string('city');
            $table->string('country')->default('Indonesia');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airports');
    }
};
