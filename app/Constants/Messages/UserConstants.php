<?php

namespace App\Constants\Messages;

class UserConstants
{
    public const USERS_FETCH_SUCCESS = 'Users with tenants fetched successfully';
    public const NO_USERS_FOUND      = 'No active users found for the organization';
    public const TENANT_EXPIRED      = 'Organization subscription expired';
    public const TENANT_INACTIVE     = 'Organization is inactive';
    public const USER_INACTIVE       = 'User is inactive';
    public const FETCH_FAILED        = 'Failed to fetch users with tenants';
}
