<?php

namespace App\Constants;

class ApiResponse
{
    // HTTP Status Codes
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_VALIDATION_ERROR = 422;
    public const HTTP_SERVER_ERROR = 500;

    // Common API Response Format
    public static function format(
        bool $success,
        string $message,
        mixed $data = [],
        int $statusCode = self::HTTP_OK,
        array $meta = [],
        array $errors = []
    ): array {
        return [
            'success' => $success,
            'status'  => $statusCode,
            'message' => $message,
            'data'    => $data ?? (object)[],
            'errors'  => $errors ?? [],
            'meta'    => $meta,
        ];
    }

}
