<?php

namespace App\Repositories;

use App\Interfaces\TrainRepositoryInterface;
use App\Models\Train;

class TrainRepository implements TrainRepositoryInterface
{
    // 1. Fungsi ambil semua data kereta
    public function getAll()
    {
        // "SELECT * FROM trains"
        return Train::all();
    }

    // 2. Fungsi ambil 1 kereta berdasarkan ID
    public function getById($id)
    {
        // "SELECT * FROM trains WHERE id = $id"
        return Train::find($id);
    }

    // 3. Fungsi tambah kereta baru
    public function create(array $data)
    {
        // "INSERT INTO trains ..."
        return Train::create($data);
    }

    // 4. Fungsi update data kereta
    public function update($id, array $data)
    {
        $train = Train::find($id);
        
        // Kalau keretanya ketemu, update datanya
        if ($train) {
            $train->update($data);
            return $train;
        }
        
        return null;
    }

    // 5. Fungsi hapus kereta
    public function delete($id)
    {
        $train = Train::find($id);
        
        // Kalau keretanya ketemu, hapus
        if ($train) {
            return $train->delete();
        }
        
        return false;
    }
}