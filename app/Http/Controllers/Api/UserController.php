<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\ApiResponseService;
use App\Constants\Messages\UserConstants;

class UserController extends Controller
{
    public function allUsers()
    {
        $users = DB::table('users as u')
            ->join('tenants as t', 'u.tenant_id', '=', 't.id')
            ->select(
                't.slug as Tenant_Slug',
                't.name as Tenant_Name',
                'u.name as User_Name',
                'u.email as Email',
                DB::raw("TO_CHAR(u.created_at, 'DD-MM-YYYY') as Created_Date")
            )
            ->where('t.is_active', true)
            ->whereDate('t.expiry_date', '>=',  Carbon::now()->toDateString())
            ->where('u.is_active', true)
            ->get();

        if ($users->isEmpty()) {
            return ApiResponseService::error(
                UserConstants::NO_USERS_FOUND,
                [UserConstants::TENANT_EXPIRED, UserConstants::TENANT_INACTIVE, UserConstants::USER_INACTIVE],
                403
            );
        }

        return ApiResponseService::success(
            UserConstants::USERS_FETCH_SUCCESS,
            $users,
            200
        );
    }
}
