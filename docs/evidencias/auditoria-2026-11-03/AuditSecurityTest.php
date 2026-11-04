<?php

namespace Tests\Audit;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\ProductoImagen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Transport\ArrayTransport;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

/** Security expectations: failures reproduce defects; never run against a real database. */
class AuditSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function createApplication()
    {
        $app = parent::createApplication();
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
        $app['config']->set('database.connections.sqlite.url', null);
        $app['config']->set('session.driver', 'array');
        $app['config']->set('cache.default', 'array');
        $app['config']->set('mail.default', 'array');

        return $app;
    }

    private function entrepreneur(): User
    {
        $user = User::factory()->create(['role' => 'emprendedor', 'status' => 'activo']);
        $user->emprendedor()->create(['status' => 'activo']);

        return $user;
    }

    private function attributes(): array
    {
        $category = Categoria::create(['nombre' => 'Auditoria', 'slug' => (string) Str::uuid()]);

        return ['nombre' => 'Pieza de prueba', 'descripcion' => 'Descripcion de prueba', 'precio' => '100.00', 'categoria_id' => $category->id];
    }

    private function product(User $user): Producto
    {
        return Producto::create([...$this->attributes(), 'emprendedor_id' => $user->emprendedor->id, 'estado' => 'activo']);
    }

    public function test_baseline_owner_can_create_a_product(): void
    {
        $user = $this->entrepreneur();
        $data = $this->attributes();
        $this->actingAs($user)->post('/emprendedor/productos', $data)->assertRedirect('/emprendedor/productos');
        $this->assertDatabaseHas('productos', ['nombre' => $data['nombre'], 'emprendedor_id' => $user->emprendedor->id]);
    }

    public function test_baseline_foreign_product_cannot_be_edited(): void
    {
        $owner = $this->entrepreneur();
        $other = $this->entrepreneur();
        $product = $this->product($owner);
        $this->actingAs($other)->get('/emprendedor/productos/'.$product->id.'/editar')->assertNotFound();
    }

    public function test_baseline_entrepreneur_cannot_write_cms(): void
    {
        $this->actingAs($this->entrepreneur())->put('/admin/configuraciones', ['home_titulo' => 'No permitido'])->assertForbidden();
        $this->assertDatabaseCount('configuraciones', 0);
    }

    public function test_profile_owner_is_not_writable_through_profile_form(): void
    {
        $user = $this->entrepreneur();
        $target = User::factory()->create(['role' => 'admin']);
        $profile = $user->emprendedor;
        $this->actingAs($user)->put('/emprendedor/perfil', ['user_id' => $target->id]);
        $this->assertSame($user->id, $profile->fresh()->user_id);
    }

    public function test_product_owner_is_not_writable_through_product_form(): void
    {
        $user = $this->entrepreneur();
        $target = $this->entrepreneur();
        $product = $this->product($user);
        $data = $product->only(['nombre', 'descripcion', 'precio', 'categoria_id']);
        $this->actingAs($user)->put('/emprendedor/productos/'.$product->id, [...$data, 'emprendedor_id' => $target->emprendedor->id]);
        $this->assertSame($user->emprendedor->id, $product->fresh()->emprendedor_id);
    }

    public function test_brand_owner_is_not_writable_through_brand_form(): void
    {
        $user = $this->entrepreneur();
        $target = $this->entrepreneur();
        $brand = Marca::create(['nombre' => 'Marca propia', 'emprendedor_id' => $user->emprendedor->id]);
        $this->actingAs($user)->put('/emprendedor/marcas/'.$brand->id, ['nombre' => 'Marca propia', 'emprendedor_id' => $target->emprendedor->id]);
        $this->assertSame($user->emprendedor->id, $brand->fresh()->emprendedor_id);
    }

    public function test_product_cannot_use_another_entrepreneurs_brand(): void
    {
        $user = $this->entrepreneur();
        $other = $this->entrepreneur();
        $brand = Marca::create(['nombre' => 'Marca ajena', 'emprendedor_id' => $other->emprendedor->id]);
        $this->actingAs($user)->post('/emprendedor/productos', [...$this->attributes(), 'marca_id' => $brand->id]);
        $this->assertDatabaseMissing('productos', ['emprendedor_id' => $user->emprendedor->id, 'marca_id' => $brand->id]);
    }

    public function test_inactive_product_is_not_public_by_direct_id(): void
    {
        $product = $this->product($this->entrepreneur());
        $product->update(['estado' => 'inactivo']);
        $this->get('/producto/'.$product->id)->assertNotFound();
    }

    public function test_failed_image_limit_does_not_partially_update_product(): void
    {
        $user = $this->entrepreneur();
        $product = $this->product($user);
        for ($i = 0; $i < 10; $i++) {
            $product->imagenes()->create(['ruta' => 'audit-existing-'.$i.'.png']);
        }
        $image = UploadedFile::fake()->createWithContent('extra.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+j3ioAAAAASUVORK5CYII='));
        $data = $product->only(['nombre', 'descripcion', 'precio', 'categoria_id']);
        $this->actingAs($user)->from('/emprendedor/productos/'.$product->id.'/editar')
            ->put('/emprendedor/productos/'.$product->id, [...$data, 'nombre' => 'Cambio que debe revertirse', 'imagenes' => [$image]])
            ->assertSessionHasErrors('imagenes');
        $this->assertSame($data['nombre'], $product->fresh()->nombre);
    }

    public function test_profile_form_saves_the_name_it_offers_to_edit(): void
    {
        $user = $this->entrepreneur();
        $this->actingAs($user)->put('/emprendedor/perfil', ['name' => 'Nombre actualizado']);
        $this->assertSame('Nombre actualizado', $user->fresh()->name);
    }

    public function test_negative_price_is_rejected(): void
    {
        $this->actingAs($this->entrepreneur())->post('/emprendedor/productos', [...$this->attributes(), 'precio' => '-1.00']);
        $this->assertDatabaseMissing('productos', ['precio' => '-1.00']);
    }

    public function test_cms_rejects_a_non_image_upload(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'activo']);
        $file = UploadedFile::fake()->createWithContent('audit.txt', 'Harmless audit text; this is not an image.');
        $this->actingAs($admin)->put('/admin/configuraciones', ['home_banner_image' => $file]);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_last_active_admin_cannot_remove_own_admin_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'activo']);
        $this->actingAs($admin)->put('/admin/usuarios/'.$admin->id, [
            'name' => $admin->name, 'email' => $admin->email, 'role' => 'emprendedor', 'status' => 'activo',
        ]);
        $this->assertSame('admin', $admin->fresh()->role);
    }

    public function test_optional_product_description_does_not_cause_server_error(): void
    {
        $data = $this->attributes();
        unset($data['descripcion']);
        $this->actingAs($this->entrepreneur())->post('/emprendedor/productos', $data)->assertRedirect('/emprendedor/productos');
        $this->assertDatabaseCount('productos', 1);
    }

    public function test_categories_with_colliding_slugs_return_validation_errors(): void
    {
        Categoria::create(['nombre' => 'Tejido Fino', 'slug' => 'tejido-fino']);
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'activo']);
        $this->actingAs($admin)->post('/admin/categorias', ['nombre' => 'Tejido-Fino'])
            ->assertSessionHasErrors('nombre');
    }

    public function test_configuration_migration_down_removes_the_table_it_created(): void
    {
        $migration = require base_path('database/migrations/2026_10_19_213459_create_configuracions_table.php');
        $migration->down();
        $this->assertFalse(Schema::hasTable('configuraciones'));
    }

    public function test_entrepreneur_migration_down_removes_the_table_it_created(): void
    {
        $migration = require base_path('database/migrations/2026_09_28_210714_create_emprendedors_table.php');
        $migration->down();
        $this->assertFalse(Schema::hasTable('emprendedores'));
    }

    public function test_catalog_handles_an_array_search_without_a_server_error(): void
    {
        $response = $this->get('/productos?buscar%5B%5D=pieza');
        $this->assertLessThan(500, $response->status());
    }

    public function test_an_entrepreneur_cannot_delete_another_entrepreneurs_photo(): void
    {
        $owner = $this->entrepreneur();
        $other = $this->entrepreneur();
        $product = $this->product($owner);
        $image = $product->imagenes()->create(['ruta' => 'audit-photo.png']);
        Storage::disk('public')->put('audit-photo.png', 'Harmless fixture');
        $this->actingAs($other)->delete('/emprendedor/productos/imagen/'.$image->id)->assertForbidden();
        $this->assertDatabaseHas('producto_imagens', ['id' => $image->id]);
        Storage::disk('public')->assertExists('audit-photo.png');
    }

    public function test_own_brand_update_and_delete_preserve_product_after_brand_deletion(): void
    {
        $user = $this->entrepreneur();
        $brand = Marca::create(['nombre' => 'Original', 'emprendedor_id' => $user->emprendedor->id]);
        $product = $this->product($user);
        $product->update(['marca_id' => $brand->id]);
        $this->actingAs($user)->put('/emprendedor/marcas/'.$brand->id, ['nombre' => 'Actualizada'])->assertRedirect('/emprendedor/marcas');
        $this->assertSame('Actualizada', $brand->fresh()->nombre);
        $this->delete('/emprendedor/marcas/'.$brand->id)->assertRedirect('/emprendedor/marcas');
        $this->assertNull($product->fresh()->marca_id);
    }

    public function test_own_product_deletion_removes_photo_rows_and_files(): void
    {
        $user = $this->entrepreneur();
        $product = $this->product($user);
        $product->imagenes()->create(['ruta' => 'audit-delete.png']);
        Storage::disk('public')->put('audit-delete.png', 'fixture');
        $this->actingAs($user)->delete('/emprendedor/productos/'.$product->id)->assertRedirect('/emprendedor/productos');
        $this->assertDatabaseMissing('productos', ['id' => $product->id]);
        $this->assertDatabaseCount('producto_imagens', 0);
        Storage::disk('public')->assertMissing('audit-delete.png');
    }

    public function test_foreign_product_update_and_delete_are_both_blocked(): void
    {
        $owner = $this->entrepreneur();
        $product = $this->product($owner);
        $this->actingAs($this->entrepreneur())->put('/emprendedor/productos/'.$product->id, [...$this->attributes(), 'nombre' => 'Ajeno'])->assertNotFound();
        $this->delete('/emprendedor/productos/'.$product->id)->assertNotFound();
        $this->assertSame('Pieza de prueba', $product->fresh()->nombre);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $product = $this->product($this->entrepreneur());
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'activo']);
        $this->actingAs($admin)->delete('/admin/categorias/'.$product->categoria_id)->assertRedirect();
        $this->assertDatabaseHas('categorias', ['id' => $product->categoria_id]);
        $this->assertSame($product->categoria_id, $product->fresh()->categoria_id);
    }

    public function test_inactive_account_remains_public_but_cannot_enter_portal(): void
    {
        $user = $this->entrepreneur();
        $product = $this->product($user);
        $user->update(['status' => 'inactivo']);
        $this->get('/emprendedor/'.$user->emprendedor->id)->assertOk();
        $this->get('/producto/'.$product->id)->assertOk();
        $this->actingAs($user)->get('/emprendedor/dashboard')->assertRedirect('/emprendedor/login');
    }

    public function test_inactive_product_is_not_listed_on_the_public_creator_profile(): void
    {
        $user = $this->entrepreneur();
        $product = $this->product($user);
        $product->update(['nombre' => 'PiezaOcultaDeAuditoria', 'estado' => 'inactivo']);
        $this->get('/emprendedor/'.$user->emprendedor->id)->assertOk()->assertDontSee('PiezaOcultaDeAuditoria');
    }

    public function test_deleting_a_user_does_not_leave_public_product_files(): void
    {
        $user = $this->entrepreneur();
        $product = $this->product($user);
        $product->imagenes()->create(['ruta' => 'audit-cascade.png']);
        Storage::disk('public')->put('audit-cascade.png', 'fixture');
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'activo']);
        $this->actingAs($admin)->delete('/admin/usuarios/'.$user->id)->assertRedirect('/admin/usuarios');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseCount('producto_imagens', 0);
        Storage::disk('public')->assertMissing('audit-cascade.png');
    }

    public function test_a_failed_photo_row_insert_does_not_leave_a_partial_product(): void
    {
        $event = 'eloquent.creating: '.ProductoImagen::class;
        app('events')->listen($event, function (): void {
            throw new \RuntimeException('Injected photo persistence failure for isolated audit');
        });
        try {
            $image = UploadedFile::fake()->createWithContent('audit.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+j3ioAAAAASUVORK5CYII='));
            $this->actingAs($this->entrepreneur())->post('/emprendedor/productos', [...$this->attributes(), 'imagenes' => [$image]])->assertServerError();
            $this->assertDatabaseCount('productos', 0);
            $this->assertSame([], Storage::disk('public')->allFiles());
        } finally {
            app('events')->forget($event);
        }
    }

    public function test_an_image_filename_and_mime_header_cannot_disguise_text_content(): void
    {
        $temporary = tempnam(sys_get_temp_dir(), 'audit-upload-');
        file_put_contents($temporary, 'Harmless text, not a JPEG image.');
        try {
            $image = new UploadedFile($temporary, 'looks-like-image.jpg', 'image/jpeg', UPLOAD_ERR_OK, true);
            $this->actingAs($this->entrepreneur())->put('/emprendedor/perfil', ['imagen' => $image])->assertSessionHasErrors('imagen');
            $this->assertSame([], Storage::disk('public')->allFiles());
        } finally {
            if (file_exists($temporary)) {
                unlink($temporary);
            }
        }
    }

    public function test_profile_rejects_an_untrusted_social_link_scheme(): void
    {
        $user = $this->entrepreneur();
        $this->actingAs($user)->put('/emprendedor/perfil', ['facebook' => 'javascript:void(0)']);
        $this->assertNotSame('javascript:void(0)', $user->emprendedor->fresh()->facebook);
    }

    public function test_every_role_protected_route_rejects_the_other_role(): void
    {
        $creator = $this->entrepreneur();
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'activo']);
        $checked = 0;
        foreach (app('router')->getRoutes() as $route) {
            $middleware = $route->gatherMiddleware();
            $targetRole = in_array('role:admin', $middleware, true) ? 'admin'
                : (in_array('role:emprendedor', $middleware, true) ? 'emprendedor' : null);
            if ($targetRole === null) {
                continue;
            }
            $uri = '/'.preg_replace('/\{[^}]+\}/', '999', $route->uri());
            $this->actingAs($targetRole === 'admin' ? $creator : $admin)
                ->call($route->methods()[0], $uri)->assertForbidden();
            $checked++;
        }
        $this->assertGreaterThan(25, $checked);
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('productos', 0);
        $this->assertDatabaseCount('configuraciones', 0);
    }

    public function test_every_role_protected_route_redirects_anonymous_visitors(): void
    {
        $checked = 0;
        foreach (app('router')->getRoutes() as $route) {
            $middleware = $route->gatherMiddleware();
            $targetRole = in_array('role:admin', $middleware, true) ? 'admin'
                : (in_array('role:emprendedor', $middleware, true) ? 'emprendedor' : null);
            if ($targetRole === null) {
                continue;
            }
            $uri = '/'.preg_replace('/\{[^}]+\}/', '999', $route->uri());
            $this->call($route->methods()[0], $uri)->assertRedirect($targetRole === 'admin' ? '/login' : '/emprendedor/login');
            $checked++;
        }
        $this->assertGreaterThan(25, $checked);
        $this->assertDatabaseCount('users', 0);
    }

    public function test_unused_category_can_be_edited_and_deleted(): void
    {
        $category = Categoria::create(['nombre' => 'Inicial', 'slug' => 'inicial']);
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'activo']);
        $this->actingAs($admin)->get('/admin/categorias/'.$category->id.'/editar')->assertOk();
        $this->put('/admin/categorias/'.$category->id, ['nombre' => 'Actualizada'])->assertRedirect('/admin/categorias');
        $this->assertSame('actualizada', $category->fresh()->slug);
        $this->delete('/admin/categorias/'.$category->id)->assertRedirect('/admin/categorias');
        $this->assertDatabaseMissing('categorias', ['id' => $category->id]);
    }

    public function test_tenth_product_photo_is_accepted_and_owner_can_delete_it(): void
    {
        $user = $this->entrepreneur();
        $product = $this->product($user);
        for ($i = 0; $i < 9; $i++) {
            $product->imagenes()->create(['ruta' => 'existing-'.$i.'.png']);
        }
        $image = UploadedFile::fake()->createWithContent('tenth.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+j3ioAAAAASUVORK5CYII='));
        $this->actingAs($user)->put('/emprendedor/productos/'.$product->id, [
            ...$product->only(['nombre', 'descripcion', 'precio', 'categoria_id']), 'imagenes' => [$image],
        ])->assertRedirect('/emprendedor/productos');
        $this->assertSame(10, $product->imagenes()->count());
        $photo = $product->imagenes()->latest('id')->first();
        Storage::disk('public')->assertExists($photo->ruta);
        $this->from('/emprendedor/productos/'.$product->id.'/editar')
            ->delete('/emprendedor/productos/imagen/'.$photo->id)->assertRedirect();
        $this->assertSame(9, $product->imagenes()->count());
        Storage::disk('public')->assertMissing($photo->ruta);
    }

    public function test_valid_profile_details_and_real_image_can_be_saved(): void
    {
        $user = $this->entrepreneur();
        $image = UploadedFile::fake()->createWithContent('profile.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+j3ioAAAAASUVORK5CYII='));
        $this->actingAs($user)->put('/emprendedor/perfil', [
            'biografia' => 'Biografia actualizada', 'facebook' => 'https://www.facebook.com/example', 'imagen' => $image,
        ])->assertRedirect('/emprendedor/perfil');
        $profile = $user->emprendedor->fresh();
        $this->assertSame('Biografia actualizada', $profile->biografia);
        Storage::disk('public')->assertExists($profile->imagen);
    }

    public function test_password_reset_email_does_not_use_an_untrusted_request_host(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'activo']);
        $transport = app('mailer')->getSymfonyTransport();
        $this->assertInstanceOf(ArrayTransport::class, $transport);
        $this->post('http://untrusted.example.test/forgot-password', ['email' => $admin->email])->assertRedirect();
        $this->assertCount(1, $transport->messages());
        $message = $transport->messages()->first()->getOriginalMessage();
        $this->assertFalse(str_contains($message->getHtmlBody(), 'http://untrusted.example.test/reset-password/'),
            'El correo sintetico contiene un enlace de recuperacion con un Host no confiable.');
    }
}
