<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StartupTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_displays_the_public_landing_page(): void
    {
        $this->get('/')->assertOk()->assertViewIs('public.home');
    }

    public function test_administration_dashboard_redirects_guests_to_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_entrepreneur_dashboard_redirects_guests_to_entrepreneur_login(): void
    {
        $this->get('/emprendedor/dashboard')->assertRedirect('/emprendedor/login');
    }

    public function test_administration_login_page_loads(): void
    {
        $this->get('/login')->assertOk()->assertViewIs('admin.login');
    }

    public function test_entrepreneur_login_page_loads(): void
    {
        $this->get('/emprendedor/login')->assertOk()->assertViewIs('emprendedores.login');
    }
}
