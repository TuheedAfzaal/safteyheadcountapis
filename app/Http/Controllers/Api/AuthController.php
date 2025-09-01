<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use App\Services\ApiResponseService;
use App\Constants\ApiResponse;
use App\Constants\Messages\AuthConstants;
use App\Constants\Validations\AuthValidation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        try {
            $validated = $request->validate(
                AuthValidation::RULES['signup'],
                AuthValidation::SIGNUP_MESSAGES
            );

            $tenant = Tenant::create([
                'name'       => $validated['tenant_name'],
                'slug'       => $validated['tenant_slug'],
                'expiry_date'=> now()->addYear(),
                'is_active'  => true
            ]);

            $user = $tenant->users()->create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return ApiResponseService::success(
                AuthConstants::SIGNUP_SUCCESS,
                [
                    'tenant' => $tenant,
                    'user'   => $user->makeHidden(['password']),
                    'token'  => $user->createToken('auth-token')->plainTextToken
                ],
                ApiResponse::HTTP_CREATED
            );

        } catch (ValidationException $e) {
            return ApiResponseService::error(
                AuthConstants::VALIDATION_FAILED, // instead of hardcoded string
                $e->errors(),
                ApiResponse::HTTP_VALIDATION_ERROR
            );
        }
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate(
                AuthValidation::RULES['login'],
                AuthValidation::LOGIN_MESSAGES
            );

            $tenant = Tenant::where('slug', $validated['tenant_slug'])->first();

            if (!$tenant) {
                return ApiResponseService::error(
                    AuthConstants::INVALID_TENANT,
                    [],
                    ApiResponse::HTTP_NOT_FOUND
                );
            }

            if (!$tenant->is_active) {
                return ApiResponseService::error(
                    AuthConstants::TENANT_INACTIVE,
                    [],
                    ApiResponse::HTTP_FORBIDDEN
                );
            }

            if (!is_null($tenant->expiry_date) && $tenant->expiry_date < now()) {
                return ApiResponseService::error(
                    AuthConstants::TENANT_EXPIRED,
                    [],
                    ApiResponse::HTTP_FORBIDDEN
                );
            }

            $user = User::where('email', $validated['email'])
                ->where('tenant_id', $tenant->id)
                ->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return ApiResponseService::error(
                    AuthConstants::LOGIN_FAILED,
                    [],
                    ApiResponse::HTTP_UNAUTHORIZED
                );
            }

            if (!$user->is_active) {
                return ApiResponseService::error(
                    AuthConstants::ACCOUNT_INACTIVE,
                    [],
                    ApiResponse::HTTP_FORBIDDEN
                );
            }

            return ApiResponseService::success(
                AuthConstants::LOGIN_SUCCESS,
                [
                    'user'   => $user->makeHidden(['password']),
                    'tenant' => $tenant,
                    'token'  => $user->createToken('auth-token')->plainTextToken
                ]
            );

        } catch (ValidationException $e) {
            return ApiResponseService::error(
                AuthConstants::VALIDATION_FAILED,
                $e->errors(),
                ApiResponse::HTTP_VALIDATION_ERROR
            );
        }
    }
}
