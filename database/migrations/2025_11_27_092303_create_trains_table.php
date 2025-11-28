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
        Schema::create('trains', function (Blueprint $table) {
            $table->id(); // train_id (Otomatis BigInt)

            $table->string('train_number', 10);
            $table->string('train_name', 100);


            $table->char('origin_station_code', 3);
            $table->char('dest_station_code', 3);

            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');

            $table->decimal('base_price', 15, 2);

            $table->string('platform_number', 5)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trains');
    }
};
