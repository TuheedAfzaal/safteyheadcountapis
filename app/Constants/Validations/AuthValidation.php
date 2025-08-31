<?php

namespace App\Constants\Validations;

class AuthValidation
{
    /**
     * Validation Rules
     */
    public const RULES = [
        'signup' => [
            'tenant_name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'tenant_slug' => 'required|string|max:255|regex:/^[A-Z0-9\-]+$/|unique:tenants,slug',
            'name'        => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'email'       => 'required|string|email:rfc,dns|max:255|unique:users,email',
            'password'    => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ]
        ],
        'login' => [
            'tenant_slug' => 'required|string|regex:/^[A-Z0-9\-]+$/',
            'email'       => 'required|string|email:rfc,dns',
            'password'    => 'required|string'
        ],
        'forgot_password' => [
            'email' => 'required|string|email:rfc,dns'
        ],
        'reset_password' => [
            'token'    => 'required|string',
            'email'    => 'required|string|email:rfc,dns',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ]
        ],
        'change_password' => [
            'current_password' => 'required|string',
            'new_password'     => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ]
        ]
    ];

    /**
     * Signup Validation Messages
     */
    public const SIGNUP_MESSAGES = [
        'tenant_name.required' => 'Organization name is required',
        'tenant_name.regex'    => 'Organization name may only contain letters, spaces and hyphens',
        'tenant_slug.required' => 'Organization identifier is required',
        'tenant_slug.regex'    => 'Organization identifier may only contain UPPERCASE letters, numbers and hyphens',
        'tenant_slug.unique'   => 'This organization identifier is already taken',
        'name.required'        => 'Full name is required',
        'name.regex'           => 'Name may only contain letters, spaces and hyphens',
        'email.required'       => 'Email address is required',
        'email.email'          => 'Please enter a valid email address',
        'email.unique'         => 'This email is already registered',
        'password.required'    => 'Password is required',
        'password.confirmed'   => 'Password confirmation does not match',
        'password.min'         => 'Password must be at least 8 characters',
        'password.regex'       => 'Password must contain at least one uppercase, one lowercase, one number and one special character',
    ];

    /**
     * Login Validation Messages
     */
    public const LOGIN_MESSAGES = [
        'tenant_slug.required' => 'Organization identifier is required for login',
        'tenant_slug.regex'    => 'Invalid Organization identifier (Only contain UPPERCASE letters, numbers and hyphens)',
        'email.required'       => 'Login requires an email address',
        'email.email'          => 'Please enter a valid email address for login',
        'password.required'    => 'Password is required for login',
    ];

    /**
     * Forgot Password Validation Messages
     */
    public const FORGOT_PASSWORD_MESSAGES = [
        'email.required' => 'Email address is required to reset password',
        'email.email'    => 'Please enter a valid email address',
    ];

    /**
     * Reset Password Validation Messages
     */
    public const RESET_PASSWORD_MESSAGES = [
        'token.required'    => 'Reset token is required',
        'email.required'    => 'Email is required',
        'email.email'       => 'Please enter a valid email address',
        'password.required' => 'New password is required',
        'password.confirmed'=> 'Password confirmation does not match',
        'password.min'      => 'Password must be at least 8 characters',
        'password.regex'    => 'Password must contain at least one uppercase, one lowercase, one number and one special character',
    ];

    /**
     * Change Password Validation Messages
     */
    public const CHANGE_PASSWORD_MESSAGES = [
        'current_password.required' => 'Current password is required',
        'new_password.required'     => 'New password is required',
        'new_password.confirmed'    => 'Password confirmation does not match',
        'new_password.min'          => 'Password must be at least 8 characters',
        'new_password.regex'        => 'Password must contain at least one uppercase, one lowercase, one number and one special character',
    ];

    /**
     * Password Requirements (for UI display or policy reference)
     */
    public const PASSWORD_REQUIREMENTS = [
        'min_length'           => 8,
        'require_mixed_case'   => true,
        'require_numbers'      => true,
        'require_symbols'      => true,
        'require_uncompromised'=> true
    ];
}
