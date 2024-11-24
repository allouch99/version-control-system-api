<?php

namespace App\Services\Auth;

use App\Services\Service;

class LogoutService extends Service
{
    public function logout($request)
    {
        
        $request->user()->currentAccessToken()->delete();
        return $this->responseService->message('Signed out successfully');
    }


}
