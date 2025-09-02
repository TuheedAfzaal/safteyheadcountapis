<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom middleware for RBAC
        app('router')->aliasMiddleware('super.admin.bypass', \App\Http\Middleware\SuperAdminBypass::class);
        app('router')->aliasMiddleware('role.permission', \App\Http\Middleware\RolePermission::class);
    }
}
