<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador Colectivo',
            'email' => 'admin@artesanos.com',
            'password' => Hash::make('admin1234'),
            'role' => 'admin',
            'status' => 'activo',
        ]);
    }
}