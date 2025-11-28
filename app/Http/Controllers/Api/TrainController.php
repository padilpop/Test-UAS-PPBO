<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\TrainRepositoryInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainController extends Controller
{
    // Menggunakan Trait supaya bisa pakai function successResponse & errorResponse
    use ApiResponseTrait;

    // Property untuk menyimpan Repository
    private $trainRepository;

    /**
     * CONSTRUCTOR INJECTION (Poin OOP!)
     * Fungsinya  Meminta Laravel otomatis memasukkan Repository ke controller.
     */
    public function __construct(TrainRepositoryInterface $trainRepository)
    {
        $this->trainRepository = $trainRepository;
    }

    // 1. GET ALL (Ambil Semua Data)
    public function index()
    {
        $trains = $this->trainRepository->getAll();
        return $this->successResponse($trains, 'Data kereta berhasil diambil');
    }

    // 2. SHOW (Ambil Detail 1 Kereta)
    public function show($id)
    {
        $train = $this->trainRepository->getById($id);

        if (!$train) {
            return $this->errorResponse('Kereta tidak ditemukan', 404);
        }

        return $this->successResponse($train, 'Detail kereta ditemukan');
    }

    // 3. STORE (Tambah Kereta Baru)
    public function store(Request $request)
    {
        // Validasi input(Syarat "Fungsionalitas API")
        $validator = Validator::make($request->all(), [
            'train_name' => 'required|string',
            'origin_station' => 'required|string',
            'destination_station' => 'required|string',
            'departure_time' => 'required|date_format:Y-m-d H:i:s',
            'base_price' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        // Jika Valid, simpan di repository
        $train = $this->trainRepository->create($request->all());

        return $this->successResponse($train, 'Kereta berhasil ditambahkan', 201);
    }

    // 4. UPDATE (Edit Kereta)
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'train_name' => 'string',
            'origin_station' => 'string',
            'destination_station' => 'string',
            'departure_time' => 'date_format:Y-m-d H:i:s',
            'base_price' => 'integer',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $train = $this->trainRepository->update($id, $request->all());

        if (!$train) {
            return $this->errorResponse('Gagal update, kereta tidak ditemukan', 404);
        }

        return $this->successResponse($train, 'Kereta berhasil diupdate');
    }

    // 5. DESTROY (Hapus Kereta)
    public function destroy($id)
    {
        $deleted = $this->trainRepository->delete($id);

        if (!$deleted) {
            return $this->errorResponse('Gagal hapus, kereta tidak ditemukan', 404);
        }

        return $this->successResponse(null, 'Kereta berhasil dihapus');
    }
}
