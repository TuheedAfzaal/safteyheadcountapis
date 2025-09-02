<?php

namespace App\Constants\Validations;

class ModuleValidation
{
    public const RULES = [
        'create' => [
            'name' => 'required|string|unique:modules,name',
        ],
        'update' => [
            'name' => 'required|string|unique:modules,name,{id}',
        ],
    ];

    public const MESSAGES = [
        'name.required' => 'Module name is required.',
        'name.string' => 'Module name must be a string.',
        'name.unique' => 'Module name must be unique.',
    ];
}
