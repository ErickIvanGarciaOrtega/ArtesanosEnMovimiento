<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Mailer\Exception\TransportException;
use Tests\TestCase;

class PasswordRecoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_recovery_response_does_not_reveal_registered_emails(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'activo']);
        $inactive = User::factory()->create(['role' => 'admin', 'status' => 'inactivo']);
        $entrepreneur = User::factory()->create(['role' => 'emprendedor']);

        foreach ([$admin->email, $inactive->email, $entrepreneur->email, 'unknown@example.com'] as $email) {
            $this->from('/forgot-password')->post('/forgot-password', compact('email'))
                ->assertRedirect('/forgot-password')->assertSessionHasNoErrors()
                ->assertSessionHas('status', 'Si el correo corresponde a una cuenta administrativa activa, recibirás un enlace para restablecer la contraseña.');
        }

        Notification::assertSentTo($admin, ResetPassword::class);
        Notification::assertNotSentTo($inactive, ResetPassword::class);
        Notification::assertNotSentTo($entrepreneur, ResetPassword::class);
        Notification::assertCount(1);
    }

    public function test_valid_reset_changes_password_rotates_remember_token_and_consumes_reset_token(): void
    {
        Event::fake([PasswordReset::class]);
        $user = User::factory()->create(['role' => 'admin']);
        $rememberToken = $user->remember_token;
        $token = Password::broker('users')->createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertRedirect('/login')->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
        $this->assertNotSame($rememberToken, $user->fresh()->remember_token);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
        Event::assertDispatched(PasswordReset::class);
        $this->assertGuest('web');

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'AnotherPassword123!',
            'password_confirmation' => 'AnotherPassword123!',
        ])->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
    }

    public static function ineligibleAccounts(): array
    {
        return [['emprendedor', 'activo'], ['admin', 'inactivo']];
    }

    #[DataProvider('ineligibleAccounts')]
    public function test_reset_rejects_wrong_role_or_inactive_accounts_even_with_valid_token(string $role, string $status): void
    {
        $user = User::factory()->create(compact('role', 'status'));
        $token = Password::broker('users')->createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_invalid_and_expired_tokens_do_not_change_password(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $token = Password::broker('users')->createToken($user);

        $this->post('/reset-password', [
            'token' => 'invalid',
            'email' => $user->email,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertSessionHasErrors('email');

        $this->travel(61)->minutes();

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public static function invalidPasswords(): array
    {
        return [
            ['short'],
            ['longbutonlylowercase'],
            ['NoDigitsOrSymbolsHere'],
            [str_repeat('a', 73).'A1!'],
            [str_repeat('á', 36).'A1!'],
            ["NewPassword123!\0"],
        ];
    }

    #[DataProvider('invalidPasswords')]
    public function test_reset_requires_a_safe_password_without_hashing_errors(string $password): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->post('/reset-password', [
            'token' => Password::broker('users')->createToken($user),
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password,
        ])->assertSessionHasErrors('password');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_confirmation_must_match(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->post('/reset-password', [
            'token' => Password::broker('users')->createToken($user),
            'email' => $user->email,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'DifferentPassword123!',
        ])->assertSessionHasErrors('password');
    }

    public function test_recovery_requests_are_throttled_and_do_not_send_multiple_notifications(): void
    {
        Notification::fake();
        $user = User::factory()->create(['role' => 'admin']);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/forgot-password', ['email' => $user->email])->assertRedirect();
        }

        $this->post('/forgot-password', ['email' => $user->email])->assertStatus(429);
        Notification::assertCount(1);
    }

    public function test_reset_revokes_a_session_with_the_previous_password_fingerprint(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $oldFingerprint = Auth::guard('web')->hashPasswordForCookie($user->password);
        $token = Password::broker('users')->createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertRedirect('/login');

        $this->actingAs($user->fresh(), 'web')->withSession(['password_hash_web' => $oldFingerprint])
            ->get('/admin/dashboard')->assertRedirect('/login');
        $this->assertGuest('web');
    }

    public function test_mail_transport_failure_keeps_the_generic_recovery_response(): void
    {
        Password::shouldReceive('broker')->with('users')->andReturnSelf();
        Password::shouldReceive('sendResetLink')->once()->andThrow(new TransportException('Mail transport unavailable'));

        $this->from('/forgot-password')->post('/forgot-password', ['email' => 'admin@example.com'])
            ->assertRedirect('/forgot-password')->assertSessionHasNoErrors()->assertSessionHas('status');
    }

    public function test_reset_form_loads_with_the_email_from_the_link(): void
    {
        $this->get('/reset-password/example-token?email=admin%40example.com')
            ->assertOk()->assertViewIs('admin.auth.reset-password')->assertViewHas('email', 'admin@example.com');
    }

    public function test_malformed_email_in_reset_link_is_rejected_without_a_server_error(): void
    {
        $this->getJson('/reset-password/example-token?email[]=unexpected')
            ->assertUnprocessable()->assertJsonValidationErrors('email');
    }
}
