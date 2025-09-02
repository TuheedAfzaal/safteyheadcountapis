<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminBypass
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && $user->is_super_admin) {
            return $next($request); // Super Admin bypasses all checks
        }
        return $next($request); // Continue to next middleware
    }
}
