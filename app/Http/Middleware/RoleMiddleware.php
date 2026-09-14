<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        // Using Spatie's hasRole or a custom logic
        if (!$request->user()->hasRole(explode('|', $role))) {
            abort(403, 'Unauthorized. Access restricted to ' . $role . ' permissions.');
        }

        return $next($request);
    }
}
