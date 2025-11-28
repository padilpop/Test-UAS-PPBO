<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Kolom Wajib (Sesuai Kamus Data)
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'), // Default password 'password'
            'full_name' => fake()->name(), // Mengganti kolom 'name' lama
            'phone_number' => fake()->numerify('08##########'),

            // Kolom Enum & Date
            'role' => 'customer', // Default role adalah customer
            'gender' => fake()->randomElement(['M', 'F']), // Pilihan M atau F
            'birth_date' => fake()->date('Y-m-d', '2005-01-01'), // Tanggal lahir acak
            'nationality' => fake()->randomElement(['ID', 'MY', 'SG']), // Sesuai range di dokumen

            // Kolom Opsional (Bisa null atau diisi random)
            'nik_ktp' => fake()->numerify('################'), // 16 digit angka
            'passport_number' => null, // Default null, bisa di-override nanti
            'oauth_provider' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
