<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\ApiResponseService;
use App\Constants\ApiResponse;
use App\Constants\Messages\RoleConstants;
use App\Constants\Validations\RoleValidation;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    // Tenant Admin: Create Role
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(
                RoleValidation::RULES['create'],
                RoleValidation::MESSAGES
            );
            $user = Auth::user();
            if (!$user->is_super_admin && !$user->roles()->where('role_name', 'Tenant Admin')->exists()) {
                return ApiResponseService::error(RoleConstants::UNAUTHORIZED, [], ApiResponse::HTTP_FORBIDDEN);
            }
            $role = Role::create([
                'role_name' => $validated['role_name'],
                'tenant_id' => $user->tenant_id,
            ]);
            return ApiResponseService::success(RoleConstants::ROLE_CREATE_SUCCESS, $role, ApiResponse::HTTP_CREATED);
        } catch (ValidationException $e) {
            return ApiResponseService::error(RoleConstants::VALIDATION_FAILED, $e->errors(), ApiResponse::HTTP_VALIDATION_ERROR);
        }
    }

    // Tenant Admin: Update Role
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate(
                RoleValidation::RULES['update'],
                RoleValidation::MESSAGES
            );
            $user = Auth::user();
            $role = Role::findOrFail($id);
            if (!$user->is_super_admin && $role->tenant_id !== $user->tenant_id) {
                return ApiResponseService::error(RoleConstants::UNAUTHORIZED, [], ApiResponse::HTTP_FORBIDDEN);
            }
            $role->update(['role_name' => $validated['role_name']]);
            return ApiResponseService::success(RoleConstants::ROLE_UPDATE_SUCCESS, $role);
        } catch (ValidationException $e) {
            return ApiResponseService::error(RoleConstants::VALIDATION_FAILED, $e->errors(), ApiResponse::HTTP_VALIDATION_ERROR);
        }
    }

    // Tenant Admin: Delete Role
    public function destroy($id)
    {
        $user = Auth::user();
        $role = Role::find($id);
        if (!$role) {
            return ApiResponseService::error(RoleConstants::NOT_FOUND, [], ApiResponse::HTTP_NOT_FOUND);
        }
        if (!$user->is_super_admin && $role->tenant_id !== $user->tenant_id) {
            return ApiResponseService::error(RoleConstants::UNAUTHORIZED, [], ApiResponse::HTTP_FORBIDDEN);
        }
        $role->delete();
        return ApiResponseService::success(RoleConstants::ROLE_DELETE_SUCCESS);
    }

    // Tenant Admin: Assign Permissions to Role
    public function assignPermissions(Request $request, $roleId)
    {
        try {
            $validated = $request->validate(
                RoleValidation::RULES['assign_permissions'],
                RoleValidation::MESSAGES
            );
            $user = Auth::user();
            $role = Role::find($roleId);
            if (!$role) {
                return ApiResponseService::error(RoleConstants::NOT_FOUND, [], ApiResponse::HTTP_NOT_FOUND);
            }
            if (!$user->is_super_admin && $role->tenant_id !== $user->tenant_id) {
                return ApiResponseService::error(RoleConstants::UNAUTHORIZED, [], ApiResponse::HTTP_FORBIDDEN);
            }
            $role->permissions()->sync($validated['permission_ids']);
            return ApiResponseService::success(RoleConstants::PERMISSION_ASSIGN_SUCCESS);
        } catch (ValidationException $e) {
            return ApiResponseService::error(RoleConstants::VALIDATION_FAILED, $e->errors(), ApiResponse::HTTP_VALIDATION_ERROR);
        }
    }

    // Tenant Admin: Assign Role to User
    public function assignRoleToUser(Request $request)
    {
        try {
            $validated = $request->validate(
                RoleValidation::RULES['assign_role_to_user'],
                RoleValidation::MESSAGES
            );
            $user = Auth::user();
            $targetUser = User::find($validated['user_id']);
            if (!$targetUser) {
                return ApiResponseService::error(RoleConstants::NOT_FOUND, [], ApiResponse::HTTP_NOT_FOUND);
            }
            if (!$user->is_super_admin && $targetUser->tenant_id !== $user->tenant_id) {
                return ApiResponseService::error(RoleConstants::UNAUTHORIZED, [], ApiResponse::HTTP_FORBIDDEN);
            }
            $targetUser->roles()->sync($validated['role_ids']);
            return ApiResponseService::success(
                RoleConstants::ROLE_ASSIGN_SUCCESS,
                [
                    'User_Id' => $targetUser->id,
                    'User_Name' => $targetUser->name,
                    'Roles' => $targetUser->roles->pluck('role_name'),
                ]
            );
        } catch (ValidationException $e) {
            return ApiResponseService::error(RoleConstants::VALIDATION_FAILED, $e->errors(), ApiResponse::HTTP_VALIDATION_ERROR);
        }
    }
}
