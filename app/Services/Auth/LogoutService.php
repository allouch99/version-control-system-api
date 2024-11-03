<?php

namespace App\Services\Auth;

use App\Services\Service;

class LogoutService extends Service
{
    public function logout($request): array
    {
        $request->user()->currentAccessToken()->delete();
        return [
            'message' => 'Signed out successfully',
            'data' => null,
            'status_number' => 200,
            'error' => false,
        ];
    }


}
