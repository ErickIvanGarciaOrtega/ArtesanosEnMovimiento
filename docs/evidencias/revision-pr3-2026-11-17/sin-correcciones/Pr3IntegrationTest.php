<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\ProductoImagen;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class Pr3IntegrationTest extends TestCase
{
    use RefreshDatabase;

    private function entrepreneur(): User
    {
        $user = User::factory()->create(['role' => 'emprendedor', 'status' => 'activo']);
        $user->emprendedor()->create(['status' => 'activo']);

        return $user;
    }

    private function attributes(): array
    {
        $category = Categoria::create(['nombre' => 'Prueba', 'slug' => 'prueba']);

        return ['nombre' => 'Pieza', 'precio' => '100.00', 'categoria_id' => $category->id];
    }

    private function image(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('prueba.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+j3ioAAAAASUVORK5CYII='));
    }

    public static function arrayFilters(): array
    {
        return [['buscar'], ['search'], ['categoria_id'], ['marca_id']];
    }

    #[DataProvider('arrayFilters')]
    public function test_array_filters_do_not_break_the_catalog_or_its_form(string $filter): void
    {
        $this->get('/productos?'.$filter.'%5B%5D=pieza')->assertOk();
    }

    public function test_optional_description_is_saved_without_database_errors(): void
    {
        $user = $this->entrepreneur();
        $attributes = $this->attributes();
        $this->actingAs($user)->post('/emprendedor/productos', $attributes)
            ->assertRedirect('/emprendedor/productos')->assertSessionHasNoErrors();
        $this->assertDatabaseHas('productos', [...$attributes, 'descripcion' => '']);
    }

    public function test_profile_rejects_a_new_owner_while_saving_valid_fields(): void
    {
        $user = $this->entrepreneur();
        $other = $this->entrepreneur();
        $this->actingAs($user)->put('/emprendedor/perfil', [
            'name' => 'Nombre actualizado', 'biografia' => 'Perfil actualizado', 'user_id' => $other->id,
        ])->assertRedirect('/emprendedor/perfil')->assertSessionHasNoErrors();
        $this->assertSame('Nombre actualizado', $user->fresh()->name);
        $this->assertSame('Perfil actualizado', $user->emprendedor->fresh()->biografia);
        $this->assertSame($user->id, $user->emprendedor->fresh()->user_id);
    }

    public function test_array_website_is_rejected_and_a_bare_domain_can_be_saved(): void
    {
        $user = $this->entrepreneur();
        $this->actingAs($user)->put('/emprendedor/perfil', ['sitio_web' => ['example.com']])
            ->assertSessionHasErrors('sitio_web');
        $this->put('/emprendedor/perfil', ['sitio_web' => 'example.com'])
            ->assertRedirect('/emprendedor/perfil')->assertSessionHasNoErrors();
        $this->assertSame('https://example.com', $user->emprendedor->fresh()->sitio_web);
    }

    public function test_password_reset_url_uses_the_configured_origin_even_with_a_foreign_host(): void
    {
        config(['app.url' => 'https://artesanos.example.test']);
        $user = User::factory()->create(['role' => 'admin']);
        $this->app['url']->forceRootUrl('http://untrusted.example.test');

        try {
            $mail = (new ResetPassword('test-token'))->toMail($user);
            $this->assertSame('https://artesanos.example.test/reset-password/test-token?email='.urlencode($user->email), $mail->actionUrl);
        } finally {
            $this->app['url']->forceRootUrl(null);
        }
    }

    public function test_photo_creation_failure_removes_the_product_and_written_files(): void
    {
        Storage::fake('public');
        $user = $this->entrepreneur();
        $attributes = [...$this->attributes(), 'descripcion' => 'Original'];
        $event = 'eloquent.creating: '.ProductoImagen::class;
        app('events')->listen($event, function (): void {
            throw new \RuntimeException('Fallo de persistencia simulado');
        });

        try {
            $response = $this->actingAs($user)->from('/emprendedor/productos/crear')->post('/emprendedor/productos', [
                ...$attributes, 'imagenes' => [$this->image()],
            ])->assertRedirect('/emprendedor/productos/crear');
            $this->assertDatabaseCount('productos', 0);
            $this->assertDatabaseCount('producto_imagens', 0);
            $this->assertSame([], Storage::disk('public')->allFiles());
            $response->assertSessionHasErrors('imagenes');
        } finally {
            app('events')->forget($event);
        }
    }

    public function test_photo_update_failure_restores_product_and_keeps_previous_files(): void
    {
        Storage::fake('public');
        $user = $this->entrepreneur();
        $attributes = [...$this->attributes(), 'descripcion' => 'Original'];
        $product = Producto::create([...$attributes, 'emprendedor_id' => $user->emprendedor->id, 'estado' => 'activo']);
        $product->imagenes()->create(['ruta' => 'productos/original.png']);
        Storage::disk('public')->put('productos/original.png', 'original');
        $event = 'eloquent.creating: '.ProductoImagen::class;
        app('events')->listen($event, function (): void {
            throw new \RuntimeException('Fallo de persistencia simulado');
        });

        try {
            $response = $this->actingAs($user)->put('/emprendedor/productos/'.$product->id, [
                ...$attributes, 'nombre' => 'Cambio rechazado', 'imagenes' => [$this->image()],
            ])->assertRedirect();
            $this->assertSame('Pieza', $product->fresh()->nombre);
            $this->assertDatabaseCount('producto_imagens', 1);
            $this->assertSame(['productos/original.png'], Storage::disk('public')->allFiles());
            $response->assertSessionHasErrors('imagenes');
        } finally {
            app('events')->forget($event);
        }
    }

    public function test_product_update_rejects_a_foreign_brand_and_preserves_owner(): void
    {
        $user = $this->entrepreneur();
        $other = $this->entrepreneur();
        $attributes = [...$this->attributes(), 'descripcion' => 'Original'];
        $product = Producto::create([...$attributes, 'emprendedor_id' => $user->emprendedor->id, 'estado' => 'activo']);
        $brand = Marca::create(['nombre' => 'Ajena', 'emprendedor_id' => $other->emprendedor->id]);
        $this->actingAs($user)->put('/emprendedor/productos/'.$product->id, [
            ...$attributes, 'marca_id' => $brand->id, 'emprendedor_id' => $other->emprendedor->id,
        ])->assertSessionHasErrors('marca_id');
        $this->assertNull($product->fresh()->marca_id);
        $this->assertSame($user->emprendedor->id, $product->fresh()->emprendedor_id);
    }

    public function test_website_migration_can_be_reverted_and_applied_again(): void
    {
        $migration = require database_path('migrations/2026_11_05_160312_add_sitio_web_to_emprendedores_table.php');
        $this->assertTrue(Schema::hasColumn('emprendedores', 'sitio_web'));
        $migration->down();
        $this->assertFalse(Schema::hasColumn('emprendedores', 'sitio_web'));
        $migration->up();
        $this->assertTrue(Schema::hasColumn('emprendedores', 'sitio_web'));
    }
}
