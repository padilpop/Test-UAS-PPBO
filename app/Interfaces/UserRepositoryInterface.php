<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    // Fungsi untuk mengambil data user berdasarkan ID
    public function getById($id);

    // Fungsi untuk update data user (nama, no hp, dll)
    public function update($id, array $data);
}