<?php

namespace App\Http\Controllers\Trait;

trait ApiResponse
{
    protected function successResponse($data, string $message = '', int $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status'=> true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($errors, int $code, string $message = ''): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status'=> false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}
