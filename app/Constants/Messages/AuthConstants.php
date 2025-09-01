<?php

namespace App\Constants\Messages;

class AuthConstants
{
    // Auth Messages
    public const LOGIN_SUCCESS     = 'Login successful';
    public const LOGIN_FAILED      = 'Invalid credentials';
    public const LOGOUT_SUCCESS    = 'Successfully logged out';
    public const SIGNUP_SUCCESS    = 'Registration successful';
    public const INVALID_TENANT    = 'Invalid organization identifier';
    public const TENANT_EXPIRED    = 'Organization subscription has expired. Please contact support.';
    public const TENANT_INACTIVE    = 'Organization is inactive. Please contact support.';
    public const ACCOUNT_INACTIVE  = 'Account is inactive. Please contact support.';
    public const VALIDATION_FAILED = 'Validation failed';
}
