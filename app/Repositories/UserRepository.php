<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    // Implementasi fungsi ambil data
    public function getById($id)
    {
        return User::find($id);
    }

    // Implementasi fungsi update data
    public function update($id, array $data)
    {
        $user = User::find($id);
        
        if ($user) {
            // Kita pakai update() langsung biar simpel dan aman
            $user->update($data);
            return $user;
        }
        
        return null;
    }
}