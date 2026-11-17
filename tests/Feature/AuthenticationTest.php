<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private function entrepreneur(): User
    {
        $user = User::factory()->create(['role' => 'emprendedor', 'status' => 'activo']);
        $user->emprendedor()->create(['status' => 'activo']);

        return $user;
    }

    public function test_active_admin_can_login_and_open_administration(): void
    {
        $user = User::factory()->create(['role' => 'admin', 'status' => 'activo']);
        $this->get('/login')->assertOk();
        $sessionId = session()->getId();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/admin/dashboard')->assertSessionHas('password_hash_web');

        $this->assertAuthenticatedAs($user, 'web');
        $this->assertNotSame($sessionId, session()->getId());
        $this->get('/admin/dashboard')->assertOk();
        $this->get('/admin/usuarios')->assertOk();
        $this->get('/admin/emprendedores')->assertRedirect('/admin/usuarios');
    }

    public function test_active_entrepreneur_can_login_and_open_own_portal(): void
    {
        $user = $this->entrepreneur();

        $this->post('/emprendedor/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/emprendedor/dashboard');

        $this->assertAuthenticatedAs($user, 'web');
        $this->get('/emprendedor/dashboard')->assertOk();
        $this->get('/emprendedor/perfil')->assertOk();
        $this->get('/')->assertOk()->assertViewIs('public.home');
    }

    public static function rejectedLogins(): array
    {
        return [
            'entrepreneur at admin login' => ['/login', 'emprendedor', 'activo'],
            'admin at entrepreneur login' => ['/emprendedor/login', 'admin', 'activo'],
            'inactive admin' => ['/login', 'admin', 'inactivo'],
            'inactive entrepreneur' => ['/emprendedor/login', 'emprendedor', 'inactivo'],
        ];
    }

    #[DataProvider('rejectedLogins')]
    public function test_wrong_role_or_inactive_account_cannot_login(string $path, string $role, string $status): void
    {
        $user = User::factory()->create(compact('role', 'status'));

        $this->post($path, ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest('web');
    }

    public function test_wrong_password_does_not_authenticate_or_flash_password(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->post('/login', ['email' => $user->email, 'password' => 'wrong-password'])
            ->assertSessionHasErrors('email')->assertSessionMissing('_old_input.password');

        $this->assertGuest('web');
    }

    public function test_missing_or_inactive_profile_cannot_login(): void
    {
        $user = User::factory()->create(['role' => 'emprendedor']);

        $this->post('/emprendedor/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest('web');

        $user->emprendedor()->create(['status' => 'inactivo']);

        $this->post('/emprendedor/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest('web');
    }

    public static function adminEndpoints(): array
    {
        return [
            ['GET', '/admin/dashboard'],
            ['GET', '/admin/usuarios'],
            ['GET', '/admin/usuarios/crear'],
            ['GET', '/admin/usuarios/1/editar'],
            ['GET', '/admin/emprendedores'],
            ['POST', '/admin/usuarios'],
            ['PUT', '/admin/usuarios/1'],
            ['DELETE', '/admin/usuarios/1'],
        ];
    }

    #[DataProvider('adminEndpoints')]
    public function test_entrepreneur_cannot_access_any_administrative_action(string $method, string $path): void
    {
        $this->actingAs($this->entrepreneur(), 'web')->call($method, $path)->assertForbidden();
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('emprendedores', 1);
    }

    public static function entrepreneurEndpoints(): array
    {
        return [
            ['GET', '/emprendedor/dashboard'],
            ['GET', '/emprendedor/perfil'],
            ['PUT', '/emprendedor/perfil'],
            ['GET', '/emprendedor/marcas'],
            ['GET', '/emprendedor/marcas/crear'],
            ['POST', '/emprendedor/marcas'],
            ['GET', '/emprendedor/productos'],
            ['GET', '/emprendedor/productos/crear'],
            ['POST', '/emprendedor/productos'],
        ];
    }

    #[DataProvider('entrepreneurEndpoints')]
    public function test_admin_cannot_enter_or_mutate_the_entrepreneur_portal(string $method, string $path): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user, 'web')->call($method, $path)->assertForbidden();
        $this->assertDatabaseCount('marcas', 0);
        $this->assertDatabaseCount('productos', 0);
    }

    public function test_guest_cannot_create_an_administrator(): void
    {
        $this->post('/admin/usuarios', [
            'name' => 'Unauthorized',
            'email' => 'unauthorized@example.com',
            'password' => 'SafePassword123!',
            'role' => 'admin',
            'status' => 'activo',
        ])->assertRedirect('/login');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_account_deactivated_after_login_loses_access(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect();
        User::whereKey($user->id)->update(['status' => 'inactivo']);
        Auth::forgetGuards();

        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->assertGuest('web');
    }

    public function test_role_changed_after_login_revokes_administrative_access(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect();
        User::whereKey($user->id)->update(['role' => 'emprendedor']);
        Auth::forgetGuards();

        $this->get('/admin/dashboard')->assertForbidden();
    }

    public function test_profile_deactivated_after_login_blocks_portal(): void
    {
        $user = $this->entrepreneur();
        $this->post('/emprendedor/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect();
        $user->emprendedor()->update(['status' => 'inactivo']);
        Auth::forgetGuards();

        $this->get('/emprendedor/perfil')->assertForbidden();
    }

    public function test_changed_password_invalidates_existing_session(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect();
        User::whereKey($user->id)->update(['password' => Hash::make('ChangedPassword123!')]);
        Auth::forgetGuards();

        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->assertGuest('web');
    }

    public function test_authenticated_users_visiting_login_return_to_their_own_dashboard(): void
    {
        $this->actingAs($this->entrepreneur(), 'web')->get('/login')->assertRedirect('/emprendedor/dashboard');
    }

    public function test_logout_clears_authentication_and_private_session_data(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect();
        $oldToken = session()->token();

        $this->withSession(['private-marker' => 'private'])
            ->post('/logout')->assertRedirect('/login')->assertSessionMissing('private-marker');

        $this->assertGuest('web');
        $this->assertNotSame($oldToken, session()->token());
    }

    public function test_login_limit_is_shared_between_both_portals(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/login', ['email' => 'unknown@example.com', 'password' => 'incorrect'])
                ->assertSessionHasErrors('email');
        }

        $this->post('/emprendedor/login', ['email' => 'UNKNOWN@example.com', 'password' => 'incorrect'])
            ->assertStatus(429);
    }

    public function test_login_ip_limit_prevents_bypassing_limit_with_different_emails(): void
    {
        for ($attempt = 0; $attempt < 30; $attempt++) {
            $this->post('/login', ['email' => "unknown{$attempt}@example.com", 'password' => 'incorrect'])
                ->assertSessionHasErrors('email');
        }

        $this->post('/emprendedor/login', ['email' => 'another@example.com', 'password' => 'incorrect'])
            ->assertStatus(429);
    }

    public function test_malformed_login_fields_return_validation_errors(): void
    {
        $this->postJson('/login', ['email' => ['unexpected'], 'password' => ['unexpected']])
            ->assertUnprocessable()->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_overlong_password_is_rejected_instead_of_accepting_a_bcrypt_prefix(): void
    {
        $user = User::factory()->create(['role' => 'admin', 'password' => str_repeat('a', 72)]);

        $this->post('/login', ['email' => $user->email, 'password' => str_repeat('a', 72).'different'])
            ->assertSessionHasErrors('password');
        $this->assertGuest('web');
    }

    public function test_post_without_csrf_token_is_rejected_outside_testing_environment(): void
    {
        $this->app->detectEnvironment(fn () => 'local');

        $this->post('/login', ['email' => 'unknown@example.com', 'password' => 'incorrect'])
            ->assertStatus(419);
    }

    public function test_entrepreneur_logout_closes_the_shared_web_session(): void
    {
        $user = $this->entrepreneur();
        $this->post('/emprendedor/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect();

        $this->post('/emprendedor/logout')->assertRedirect('/emprendedor/login');
        $this->assertGuest('web');
        $this->get('/emprendedor/perfil')->assertRedirect('/emprendedor/login');
    }

    public function test_removed_profile_blocks_an_existing_session_before_controller_execution(): void
    {
        $user = $this->entrepreneur();
        $this->post('/emprendedor/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect();
        $user->emprendedor()->delete();
        Auth::forgetGuards();

        $this->put('/emprendedor/perfil', ['name' => 'Changed'])->assertForbidden();
        $this->assertSame($user->name, $user->fresh()->name);
    }

    public function test_admin_cannot_create_users_with_a_weak_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin, 'web')->post('/admin/usuarios', [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'weakpass',
            'role' => 'emprendedor',
            'status' => 'activo',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseCount('users', 1);
    }

    public function test_administrative_password_changes_apply_the_same_password_policy(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = $this->entrepreneur();
        $attributes = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'emprendedor',
            'status' => 'activo',
        ];

        $this->actingAs($admin, 'web')->put('/admin/usuarios/'.$user->id, [...$attributes, 'password' => 'weakpass'])
            ->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('password', $user->fresh()->password));

        $this->put('/admin/usuarios/'.$user->id, [...$attributes, 'password' => 'ChangedPassword123!'])
            ->assertRedirect('/admin/usuarios');
        $this->assertTrue(Hash::check('ChangedPassword123!', $user->fresh()->password));
    }
}
