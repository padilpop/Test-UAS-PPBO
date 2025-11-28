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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('booking_id'); // Primary Key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // INI KUNCINYA: Polymorphic Columns (ticketable_id & ticketable_type)
            // Akan menyimpan ID entitas (misal: 1) dan Tipe entitas (misal: App\Models\Flight)
            $table->morphs('ticketable');

            $table->string('booking_code', 20)->unique();
            $table->decimal('total_price', 15, 2);
            $table->enum('payment_status', ['unpaid', 'paid', 'cancelled', 'expired'])->default('unpaid');
            $table->string('snap_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
