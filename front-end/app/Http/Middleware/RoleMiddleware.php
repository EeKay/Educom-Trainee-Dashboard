<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $userRole = session('role');

        if (!$userRole || !in_array($userRole, $roles, true)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}