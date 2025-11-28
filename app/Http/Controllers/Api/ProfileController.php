<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\UserRepositoryInterface; // Interface User
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    private $userRepository;

    // Dependency Injection Repository User
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // 1. GET PROFILE (Lihat Data Diri Sendiri)
    public function show(Request $request)
    {
        // Ambil ID otomatis dari Token user yang sedang login
        $userId = $request->user()->id;

        $user = $this->userRepository->getById($userId);

        return $this->successResponse($user, 'Profil berhasil diambil');
    }

    // 2. UPDATE PROFILE (Edit Data Diri Sendiri)
    public function update(Request $request)
    {
        $userId = $request->user()->id;

        // Validasi input (Nama, HP, NIK, Paspor, dll)
        $validator = Validator::make($request->all(), [
            'full_name'       => 'string|max:100',
            'phone_number'    => 'string|max:15',
            'nik_ktp'         => 'nullable|string|max:16',
            'passport_number' => 'nullable|string|max:10',
            'birth_date'      => 'date',
            'gender'          => 'in:M,F',
            'nationality'     => 'string|size:2',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        // Update data pakai Repository
        $user = $this->userRepository->update($userId, $request->all());

        if (!$user) {
            return $this->errorResponse('Gagal update profil', 500);
        }

        return $this->successResponse($user, 'Profil berhasil diperbarui');
    }
}
