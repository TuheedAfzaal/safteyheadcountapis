<?php

namespace App\Constants\Validations;

class ActionValidation
{
    public const RULES = [
        'create' => [
            'name' => 'required|string',
            'module_id' => 'required|exists:modules,id',
        ],
        'update' => [
            'name' => 'required|string',
        ],
    ];

    public const MESSAGES = [
        'name.required' => 'Action name is required.',
        'name.string' => 'Action name must be a string.',
        'module_id.required' => 'Module is required.',
        'module_id.exists' => 'Module does not exist.',
    ];
}
