<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\RegisterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    protected $registerService;
    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function register(Request $request): JsonResponse
    {
        $response = $this->registerService->createAccount($request);

         return response()->json([
                'message' => $response['message'],
                'data' => $response['data'],
                'error' => $response['error'],
            ],$response['status_number']);

    }
}
