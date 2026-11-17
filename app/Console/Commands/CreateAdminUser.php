<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {--name=} {--email=}';

    protected $description = 'Crea un administrador activo solicitando su contraseña de forma oculta';

    public function handle(): int
    {
        if (! $this->input->isInteractive()) {
            $this->error('Ejecuta php artisan admin:create de forma interactiva para introducir la contraseña.');

            return self::FAILURE;
        }

        $attributes = [
            'name' => $this->option('name') ?? $this->ask('Nombre'),
            'email' => $this->option('email') ?? $this->ask('Correo electrónico'),
            'password' => $this->secret('Contraseña (12 caracteres, mayúscula, minúscula, número y símbolo)'),
            'password_confirmation' => $this->secret('Confirma la contraseña'),
        ];

        $validator = Validator::make($attributes, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'max:1024', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        User::create([
            ...$validator->validated(),
            'role' => 'admin',
            'status' => 'activo',
        ]);

        $this->info('Administrador creado correctamente.');

        return self::SUCCESS;
    }
}
