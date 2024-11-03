<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Auth\LoginService;

class LoginController extends Controller
{
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }
    public function login(Request $request): JsonResponse
    {
        $response = $this->loginService->login($request);

        return response()->json([
            'message' => $response['message'],
            'data' => $response['data'],
            'error' => $response['error'],
        ],$response['status_number']);

    }

}
