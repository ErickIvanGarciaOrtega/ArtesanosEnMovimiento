<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProvisioningTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_do_not_create_accounts_with_known_passwords(): void
    {
        $this->seed();

        $this->assertDatabaseCount('users', 0);
    }

    public function test_command_creates_an_admin_with_a_hidden_password(): void
    {
        $this->artisan('admin:create', ['--name' => 'Administrador', '--email' => 'admin@example.com'])
            ->expectsQuestion('Contraseña (12 caracteres, mayúscula, minúscula, número y símbolo)', 'SafePassword123!')
            ->expectsQuestion('Confirma la contraseña', 'SafePassword123!')
            ->expectsOutput('Administrador creado correctamente.')
            ->assertSuccessful();

        $user = User::where('email', 'admin@example.com')->firstOrFail();
        $this->assertSame('admin', $user->role);
        $this->assertSame('activo', $user->status);
        $this->assertTrue(Hash::check('SafePassword123!', $user->password));
    }

    public function test_command_does_not_overwrite_an_existing_account(): void
    {
        $user = User::factory()->create(['email' => 'existing@example.com', 'role' => 'emprendedor']);

        $this->artisan('admin:create', ['--name' => 'Administrator', '--email' => $user->email])
            ->expectsQuestion('Contraseña (12 caracteres, mayúscula, minúscula, número y símbolo)', 'SafePassword123!')
            ->expectsQuestion('Confirma la contraseña', 'SafePassword123!')
            ->assertFailed();

        $this->assertDatabaseCount('users', 1);
        $this->assertSame('emprendedor', $user->fresh()->role);
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_command_rejects_weak_passwords(): void
    {
        $this->artisan('admin:create', ['--name' => 'Administrator', '--email' => 'admin@example.com'])
            ->expectsQuestion('Contraseña (12 caracteres, mayúscula, minúscula, número y símbolo)', 'short')
            ->expectsQuestion('Confirma la contraseña', 'short')
            ->assertFailed();

        $this->assertDatabaseCount('users', 0);
    }

    public function test_command_requires_interactive_password_entry(): void
    {
        $this->artisan('admin:create', ['--name' => 'Administrator', '--email' => 'admin@example.com', '--no-interaction' => true])
            ->assertFailed();

        $this->assertDatabaseCount('users', 0);
    }
}
