<?php

use App\Http\Controllers\Api\AuthController;

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require auth)
Route::middleware(['auth:sanctum', 'super.admin.bypass'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);


    // User permissions endpoint (for frontend RBAC)
    Route::get('/user/permissions', [\App\Http\Controllers\Api\AuthController::class, 'userPermissions']);

    // Role management (Tenant Admin or Super Admin)
    Route::post('/roles', [\App\Http\Controllers\Api\RoleController::class, 'store']);
    Route::put('/roles/{id}', [\App\Http\Controllers\Api\RoleController::class, 'update']);
    Route::delete('/roles/{id}', [\App\Http\Controllers\Api\RoleController::class, 'destroy']);
    Route::post('/roles/{id}/permissions', [\App\Http\Controllers\Api\RoleController::class, 'assignPermissions']);
    Route::post('/roles/assign', [\App\Http\Controllers\Api\RoleController::class, 'assignRoleToUser']);

    // Permission management
    Route::post('/permissions', [\App\Http\Controllers\Api\PermissionController::class, 'store']);
    Route::get('/permissions', [\App\Http\Controllers\Api\PermissionController::class, 'index']);

    // Super Admin: Module management
    Route::middleware('role.permission:Modules,manage')->group(function () {
    Route::post('/modules/list', [\App\Http\Controllers\Api\ModuleController::class, 'index']);
    Route::post('/modules/create', [\App\Http\Controllers\Api\ModuleController::class, 'store']);
    Route::post('/modules/update', [\App\Http\Controllers\Api\ModuleController::class, 'update']);
    Route::post('/modules/delete', [\App\Http\Controllers\Api\ModuleController::class, 'destroy']);

    // Action management (all via payload)
    Route::post('/actions/list', [\App\Http\Controllers\Api\ActionController::class, 'index']);
    Route::post('/actions/create', [\App\Http\Controllers\Api\ActionController::class, 'store']);
    Route::post('/actions/update', [\App\Http\Controllers\Api\ActionController::class, 'update']);
    Route::post('/actions/delete', [\App\Http\Controllers\Api\ActionController::class, 'destroy']);
    });
});