<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolePermission
{
    public function handle(Request $request, Closure $next, $module, $action)
    {
        $user = $request->user();
        if ($user && $user->is_super_admin) {
            return $next($request); // Super Admin bypasses all checks
        }
        $hasPermission = $user->roles()->whereHas('permissions', function($q) use ($module, $action) {
            $q->where('module', $module)->where('action', $action);
        })->exists();
        if (!$hasPermission) {
            return response()->json([
                'User_Name' => $user->name,
                'Tenant' => $user->tenant->name ?? null,
                'Module' => $module,
                'Action' => $action,
                'Access' => false,
                'Message' => "You do not have permission to $action $module."
            ], 403);
        }
        return $next($request);
    }
}
