<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // 1. REGISTER
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'email'        => 'required|string|email|max:100|unique:users',
            'password'     => 'required|string|min:8|confirmed',
            'full_name'    => 'required|string|max:100',
            'phone_number' => 'required|string|max:15',
            'gender'       => 'required|in:M,F',
            'birth_date'   => 'required|date',
            'nationality'  => 'required|string|size:2',

            'nik_ktp'         => 'nullable|string|max:16',
            'passport_number' => 'nullable|string|max:10',
        ]);

        // Buat user baru
        $user = User::create([
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'full_name'       => $request->full_name,
            'phone_number'    => $request->phone_number,
            'gender'          => $request->gender,
            'birth_date'      => $request->birth_date,
            'nationality'     => $request->nationality,
            'role'            => 'customer', // Default user biasa

            // Data opsional
            'nik_ktp'         => $request->nik_ktp,
            'passport_number' => $request->passport_number,
        ]);

        // Buat token otomatis setelah register agar langsung login
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Registrasi berhasil',
            'data'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ], 201);
    }

    // 2. LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // Cek apakah user ada dan password benar
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        // Buat token baru
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // 3. LOGOUT
    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    // 4. ME (Cek User Login)
    public function me(Request $request)
    {
        return response()->json([
            'message' => 'Data user berhasil diambil',
            'data' => $request->user()
        ]);
    }
}
