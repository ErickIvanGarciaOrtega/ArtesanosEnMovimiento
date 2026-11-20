<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->warn('Crea la cuenta administrativa con php artisan admin:create. No se generan credenciales predeterminadas.');
    }
}
