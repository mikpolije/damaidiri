<?php

namespace App\Traits;

trait ApiTrait
{
    private function apiResponse(string $status, string $message, $data, int $statusCode)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}