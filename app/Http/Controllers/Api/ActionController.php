<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Action;
use App\Models\Module;
use App\Services\ApiResponseService;
use App\Constants\ApiResponse;
use Illuminate\Support\Facades\Auth;

use App\Constants\Messages\ActionConstants;
use App\Constants\Validations\ActionValidation;

class ActionController extends Controller
{
    // Super Admin: List Actions for a Module (payload only)
    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();
        try {
            $validated = $request->validate([
                'module_id' => ActionValidation::RULES['create']['module_id'],
            ], ActionValidation::MESSAGES);
            $actions = Action::where('module_id', $validated['module_id'])->get();
            return ApiResponseService::success(ActionConstants::ACTION_LIST_SUCCESS ?? 'Actions fetched successfully.', $actions);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponseService::error(ActionConstants::VALIDATION_FAILED, $e->errors(), ApiResponse::HTTP_VALIDATION_ERROR);
        }
    }

    // Super Admin: Create Action for a Module (payload only)
    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();
        try {
            $validated = $request->validate([
                'name' => ActionValidation::RULES['create']['name'],
                'module_id' => 'required|exists:modules,id',
            ], ActionValidation::MESSAGES);
            $action = Action::create([
                'name' => $validated['name'],
                'module_id' => $validated['module_id']
            ]);
            return ApiResponseService::success(ActionConstants::ACTION_CREATE_SUCCESS, $action, ApiResponse::HTTP_CREATED);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponseService::error(ActionConstants::VALIDATION_FAILED, $e->errors(), ApiResponse::HTTP_VALIDATION_ERROR);
        }
    }

    // Super Admin: Update Action (payload only)
    public function update(Request $request)
    {
        $this->authorizeSuperAdmin();
        try {
            $validated = $request->validate([
                'id' => 'required|exists:actions,id',
                ...ActionValidation::RULES['update'],
            ], ActionValidation::MESSAGES);
            $action = Action::findOrFail($validated['id']);
            $action->update(['name' => $validated['name']]);
            return ApiResponseService::success(ActionConstants::ACTION_UPDATE_SUCCESS, $action);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponseService::error(ActionConstants::VALIDATION_FAILED, $e->errors(), ApiResponse::HTTP_VALIDATION_ERROR);
        }
    }

    // Super Admin: Delete Action (payload only)
    public function destroy(Request $request)
    {
        $this->authorizeSuperAdmin();
        try {
            $validated = $request->validate([
                'id' => 'required|exists:actions,id',
            ], [
                'id.required' => 'Action ID is required.',
                'id.exists' => 'Action does not exist.',
            ]);
            $action = Action::find($validated['id']);
            if (!$action) {
                return ApiResponseService::error(ActionConstants::NOT_FOUND, [], ApiResponse::HTTP_NOT_FOUND);
            }
            $action->delete();
            return ApiResponseService::success(ActionConstants::ACTION_DELETE_SUCCESS);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponseService::error(ActionConstants::VALIDATION_FAILED, $e->errors(), ApiResponse::HTTP_VALIDATION_ERROR);
        }
    }

    private function authorizeSuperAdmin()
    {
        $user = Auth::user();
        abort_unless($user && $user->is_super_admin, 403, 'Only super admin can manage actions.');
    }
}
