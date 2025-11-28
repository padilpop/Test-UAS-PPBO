<?php

namespace App\Traits;

trait ApiResponseTrait
{
    /**
     * Balikan respon sukses (Code 200/201)
     * Access Modifier: protected (Cuma bisa dipanggil oleh Controller yang pakai Trait ini)
     */
    protected function successResponse($data, $message = 'Success', $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Balikan respon error (Code 400/404/422)
     */
    protected function errorResponse($message, $code = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => null,
        ], $code);
    }
}