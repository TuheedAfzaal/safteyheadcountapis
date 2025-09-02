<?php

namespace App\Constants\Validations;

class PermissionValidation
{
    public const RULES = [
        'create' => [
            'module' => 'required|string|max:255',
            'action' => 'required|string|max:255',
        ],
    ];

    public const MESSAGES = [
        'module.required' => 'Module name is required.',
        'module.string' => 'Module name must be a string.',
        'module.max' => 'Module name may not be greater than 255 characters.',
        'action.required' => 'Action is required.',
        'action.string' => 'Action must be a string.',
        'action.max' => 'Action may not be greater than 255 characters.',
    ];
}
