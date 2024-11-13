<?php


namespace App\Traits;

trait ResponseTrait
{
    public function successResponse($message, $data = [], $status = 200){
         return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data ], $status);
    }
    public function errorResponse($message, $data = [], $status = 400){
        return response()->json([ 
            'status' => 'error',
            'message' => $message,
            'data' => $data ], $status);
        }
}
