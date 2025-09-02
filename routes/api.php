<?php

use App\Http\Controllers\Api\AuthController;

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require auth)
Route::middleware(['auth:sanctum', 'super.admin.bypass'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Role management (Tenant Admin or Super Admin)
    Route::post('/roles', [\App\Http\Controllers\Api\RoleController::class, 'store']);
    Route::put('/roles/{id}', [\App\Http\Controllers\Api\RoleController::class, 'update']);
    Route::delete('/roles/{id}', [\App\Http\Controllers\Api\RoleController::class, 'destroy']);
    Route::post('/roles/{id}/permissions', [\App\Http\Controllers\Api\RoleController::class, 'assignPermissions']);
    Route::post('/roles/assign', [\App\Http\Controllers\Api\RoleController::class, 'assignRoleToUser']);

    // Permission management
    Route::post('/permissions', [\App\Http\Controllers\Api\PermissionController::class, 'store']);
    Route::get('/permissions', [\App\Http\Controllers\Api\PermissionController::class, 'index']);

    // Example: Protect a module/action with RBAC
    Route::middleware('role.permission:Invoices,delete')->delete('/invoices/{id}', function () {
        // ...delete logic...
        return response()->json(['message' => 'Invoice deleted (example route)']);
    });
});