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
        return  $this->logoutService->logout($request)->jsonResponse();

    }
}
