<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Airport;
use App\Models\Airline; // Wajib ada model ini
use App\Models\User;
use App\Models\Train;
use App\Models\Wagon;
use App\Models\TrainSeat;
use App\Models\Flight;
use App\Models\FlightSeat;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedMasterData(); // Bandara & Maskapai (PENTING!)
        $this->seedUsers();
        $this->seedTrains();
        $this->seedFlights();    // Pesawat (Sekarang aman karena Maskapai sudah dibuat duluan)
    }

    private function seedMasterData()
    {
        // 1. Bandara
        Airport::create([
            'airport_code' => 'CGK',
            'airport_name' => 'Soekarno Hatta',
            'city' => 'Jakarta',
            'country' => 'Indonesia'
        ]);

        Airport::create([
            'airport_code' => 'DPS',
            'airport_name' => 'Ngurah Rai',
            'city' => 'Bali',
            'country' => 'Indonesia'
        ]);

        // 2. Maskapai (WAJIB DIBUAT DULUAN)
        Airline::create([
            'code' => 'GIA',
            'name' => 'Garuda Indonesia',
            'logo_url' => 'https://bit.ly/garuda-logo'
        ]);

        Airline::create([
            'code' => 'LION',
            'name' => 'Lion Air',
            'logo_url' => 'https://bit.ly/lion-logo'
        ]);
    }

    private function seedUsers()
    {
        User::factory()->create([
            'full_name' => 'Super Admin',
            'email' => 'admin@tiket.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone_number' => '081234567890',
            'gender' => 'M',
            'nationality' => 'ID',
            'nik_ktp' => '1234567890123456',
            'birth_date' => '1990-01-01',
        ]);

        User::factory()->create([
            'full_name' => 'Budi Customer',
            'email' => 'user@tiket.com',
            'password' => Hash::make('user123'),
            'role' => 'customer',
            'phone_number' => '089876543210',
            'gender' => 'M',
            'nationality' => 'ID',
            'birth_date' => '2000-05-20',
        ]);

        User::factory(5)->create();
    }

    private function seedFlights()
    {
        // Ambil ID Maskapai Garuda yang tadi dibuat
        $garuda = Airline::where('code', 'GIA')->first();

        if (!$garuda) return; // Jaga-jaga kalau maskapai belum ada

        $flight = Flight::create([
            'flight_number' => 'GA-404',
            'airline_id' => $garuda->id, // PAKE ID, BUKAN NAMA
            'origin_airport_code' => 'CGK', // Sesuaikan nama kolom migrasi
            'destination_airport_code' => 'DPS', // Sesuaikan nama kolom migrasi
            'departure_time' => now()->addDays(1)->setHour(10)->setMinute(0),
            'arrival_time' => now()->addDays(1)->setHour(12)->setMinute(50),
            'price' => 1500000, // Nama kolom 'price', bukan 'base_price'
            // 'gate' => 'G-24', // Uncomment jika kolom gate sudah ada di migrasi (sepertinya ada file add_gate tadi)
        ]);

        // Kursi Pesawat
        $rows = 2;
        $cols = ['A', 'B', 'C', 'D', 'E', 'F'];

        foreach (range(1, $rows) as $row) {
            foreach ($cols as $col) {
                FlightSeat::create([
                    'flight_id' => $flight->id,
                    'seat_number' => $row . $col,
                    'is_available' => true,
                    'class' => 'economy',
                ]);
            }
        }
    }

    private function seedTrains()
    {
        $train = Train::create([
            'train_number' => 'KA-88',
            'train_name' => 'Argo Bromo Anggrek',
            'origin_station_code' => 'GMR',
            'dest_station_code' => 'SBI',
            'departure_time' => now()->addDays(2)->setHour(8)->setMinute(0),
            'arrival_time' => now()->addDays(2)->setHour(16)->setMinute(0),
            'base_price' => 500000,
            'platform_number' => '1',
        ]);

        $wagon = Wagon::create([
            'train_id' => $train->id,
            'name' => 'Eksekutif 1',
            'class' => 'executive'
        ]);

        for ($i = 1; $i <= 5; $i++) {
            TrainSeat::create([
                'wagon_id' => $wagon->id,
                'seat_number' => '1' . ['A', 'B', 'C', 'D'][($i - 1) % 4],
                'is_available' => true,
            ]);
        }
    }
}
