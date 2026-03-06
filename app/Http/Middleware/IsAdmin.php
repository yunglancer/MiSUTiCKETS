<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario está logueado Y su rol es 'admin', lo dejamos pasar
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Si es un cliente normal intentando entrar al panel de admin, lo pateamos a su panel
        return redirect()->route('client.dashboard')->with('error', 'Acceso denegado. Área exclusiva para organizadores.');
    }
}