<?php

namespace Tests\Feature;

use Tests\TestCase;

class RouteRegistrationTest extends TestCase
{
    public function test_route_names_are_unique_and_controller_actions_exist(): void
    {
        $names = [];

        foreach (app('router')->getRoutes() as $route) {
            if ($route->getName() !== null) {
                $this->assertNotContains($route->getName(), $names);
                $names[] = $route->getName();
            }

            if ($action = $route->getAction('controller')) {
                $parts = explode('@', $action);
                $controller = $parts[0];
                $method = $parts[1] ?? '__invoke';
                $this->assertTrue(method_exists($controller, $method), $action);
            }
        }
    }

    public function test_each_private_area_has_authentication_session_and_role_middleware(): void
    {
        foreach (app('router')->getRoutes() as $route) {
            $role = str_starts_with($route->uri(), 'admin/') ? 'admin' : 'emprendedor';

            if (str_starts_with($route->uri(), 'admin/')
                || (str_starts_with($route->uri(), 'emprendedor/') && ! in_array($route->uri(), ['emprendedor/login', 'emprendedor/logout', 'emprendedor/{id}']))) {
                $this->assertContains('auth:web', $route->gatherMiddleware(), $route->uri());
                $this->assertContains('auth.session', $route->gatherMiddleware(), $route->uri());
                $this->assertContains('role:'.$role, $route->gatherMiddleware(), $route->uri());
            }
        }
    }

    public function test_routes_can_be_cached_for_deployment(): void
    {
        try {
            $this->artisan('route:cache')->assertSuccessful();
        } finally {
            $this->artisan('route:clear')->assertSuccessful();
        }
    }

    public function test_obsolete_nested_login_url_is_not_registered(): void
    {
        $this->get('/admin/emprendedor/login')->assertNotFound();
    }

    public function test_obsolete_entrepreneur_creation_action_does_not_silently_redirect(): void
    {
        $this->post('/admin/emprendedores')->assertStatus(405);
    }
}
