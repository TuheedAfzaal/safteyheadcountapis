<?php

namespace App\Services;

use App\Constants\ApiResponse;

class ApiResponseService
{
    public static function send(
        bool $success,
        string $message,
        mixed $data = [],
        int $statusCode = 200,
        array $meta = [],
        array $errors = []
    ) {
        return response()->json(
            ApiResponse::format($success, $message, $data, $statusCode, $meta, $errors),
            $statusCode
        );
    }

    public static function success(
        string $message = ApiResponse::SUCCESS,
        mixed $data = [],
        int $statusCode = ApiResponse::HTTP_OK,
        array $meta = []
    ) {
        return self::send(true, $message, $data, $statusCode, $meta, []);
    }

    public static function error(
        string $message = ApiResponse::ERROR,
        array $errors = [],
        int $statusCode = ApiResponse::HTTP_BAD_REQUEST,
        array $meta = []
    ) {
        return self::send(false, $message, (object)[], $statusCode, $meta, $errors);
    }


}