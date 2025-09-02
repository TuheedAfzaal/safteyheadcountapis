<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Services\ApiResponseService;
use App\Constants\ApiResponse;
use App\Constants\Messages\ModuleConstants;
use App\Constants\Validations\ModuleValidation;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    // Super Admin: List Modules
    public function index()
    {
        $this->authorizeSuperAdmin();
        return ApiResponseService::success('Modules fetched successfully.', Module::all());
    }

    // Super Admin: Create Module
    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();
        try {
            $validated = $request->validate(
                ModuleValidation::RULES['create'],
                ModuleValidation::MESSAGES
            );
            $module = Module::create(['name' => $validated['name']]);
            return ApiResponseService::success(ModuleConstants::MODULE_CREATE_SUCCESS, $module, ApiResponse::HTTP_CREATED);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponseService::error(ModuleConstants::VALIDATION_FAILED, $e->errors(), ApiResponse::HTTP_VALIDATION_ERROR);
        }
    }

    // Super Admin: Update Module
    public function update(Request $request, $id)
    {
        $this->authorizeSuperAdmin();
        try {
            $rules = ModuleValidation::RULES['update'];
            $rules['name'] = str_replace('{id}', $id, $rules['name']);
            $validated = $request->validate($rules, ModuleValidation::MESSAGES);
            $module = Module::findOrFail($id);
            $module->update(['name' => $validated['name']]);
            return ApiResponseService::success(ModuleConstants::MODULE_UPDATE_SUCCESS, $module);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponseService::error(ModuleConstants::VALIDATION_FAILED, $e->errors(), ApiResponse::HTTP_VALIDATION_ERROR);
        }
    }

    // Super Admin: Delete Module
    public function destroy($id)
    {
        $this->authorizeSuperAdmin();
        $module = Module::find($id);
        if (!$module) {
            return ApiResponseService::error(ModuleConstants::NOT_FOUND, [], ApiResponse::HTTP_NOT_FOUND);
        }
        $module->delete();
        return ApiResponseService::success(ModuleConstants::MODULE_DELETE_SUCCESS);
    }

    private function authorizeSuperAdmin()
    {
        $user = Auth::user();
        abort_unless($user && $user->is_super_admin, 403, 'Only super admin can manage modules.');
    }
}
