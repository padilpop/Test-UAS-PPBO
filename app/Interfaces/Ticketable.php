<?php

namespace App\Interfaces;

interface Ticketable
{
    // Setiap entitas tiket harus bisa memberikan info harga dasar
    public function getBasePrice();

    // Setiap entitas harus punya kode unik (misal: GA-123 atau KA-88)
    public function getCode();

    // Fungsi untuk mengurangi stok kursi (nanti diimplementasikan)
    public function decrementSeat($seatId);
}
