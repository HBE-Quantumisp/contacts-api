<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Para APIs, no redirigir sino retornar null para que Laravel maneje como error 401
        if ($request->expectsJson() || $request->is('api/*')) {
            return null;
        }

        // Para rutas web (si las hubiera), redirigir al login
        return route('login');
    }
}
