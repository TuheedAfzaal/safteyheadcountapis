<?php

namespace App\Constants\Validations;

class RoleValidation
{
    public const RULES = [
        'create' => [
            'role_name' => 'required|string|max:255',
        ],
        'update' => [
            'role_name' => 'required|string|max:255',
        ],
        'assign_permissions' => [
            'permission_ids' => 'required|array|min:1',
            'permission_ids.*' => 'exists:permissions,id',
        ],
        'assign_role_to_user' => [
            'user_id' => 'required|exists:users,id',
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'exists:roles,id',
        ],
    ];

    public const MESSAGES = [
        'role_name.required' => 'Role name is required.',
        'role_name.string' => 'Role name must be a string.',
        'role_name.max' => 'Role name may not be greater than 255 characters.',
        'permission_ids.required' => 'At least one permission must be selected.',
        'permission_ids.array' => 'Permissions must be an array.',
        'permission_ids.*.exists' => 'Selected permission does not exist.',
        'user_id.required' => 'User is required.',
        'user_id.exists' => 'Selected user does not exist.',
        'role_ids.required' => 'At least one role must be selected.',
        'role_ids.array' => 'Roles must be an array.',
        'role_ids.*.exists' => 'Selected role does not exist.',
    ];
}
