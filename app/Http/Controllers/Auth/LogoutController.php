<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\LogoutService;

class LogoutController extends Controller
{
    protected $logoutService;

    public function __construct(LogoutService $logoutService)
    {
        $this->logoutService = $logoutService;
    }
    public function logout(Request $request)
    {

        $response = $this->logoutService->logout($request);

        return response()->json([
            'message' => $response['message'],
            'data' => $response['data'],
            'error' => $response['error'],
        ],$response['status_number']);

    }
}
