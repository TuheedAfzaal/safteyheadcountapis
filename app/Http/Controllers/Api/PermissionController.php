<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Services\ApiResponseService;
use App\Constants\ApiResponse;
use App\Constants\Messages\PermissionConstants;
use App\Constants\Validations\PermissionValidation;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller
{
    // Super Admin or Tenant Admin: Create Permission
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(
                PermissionValidation::RULES['create'],
                PermissionValidation::MESSAGES
            );
            $permission = Permission::create($validated);
            return ApiResponseService::success(PermissionConstants::PERMISSION_CREATE_SUCCESS, $permission, ApiResponse::HTTP_CREATED);
        } catch (ValidationException $e) {
            return ApiResponseService::error(PermissionConstants::VALIDATION_FAILED, $e->errors(), ApiResponse::HTTP_VALIDATION_ERROR);
        }
    }

    // List all permissions
    public function index()
    {
        return ApiResponseService::success('Permissions fetched successfully.', Permission::all());
    }
}
