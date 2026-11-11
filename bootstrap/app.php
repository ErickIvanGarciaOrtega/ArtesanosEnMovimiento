<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // S05 FIX: Protege contra Host Header Injection forzando a Laravel a confiar 
        // solo en tu dominio real y en el localhost para las pruebas.
        $middleware->trustHosts(at: [
            'artesanosyemprendedores.com', // Cuando subas tu página a internet, pon tu dominio final aquí
            'localhost',
            '127.0.0.1'
        ]);

        // Redirección condicional precisa para usuarios no autenticados
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('emprendedor/*') || $request->is('emprendedor')) {
                return route('emprendedor.login');
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();