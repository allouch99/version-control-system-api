<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginService extends Service
{
    public function rule(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }
    protected function validator($request,$rule): array
    {
        return Validator::make($request->all(), $rule)->errors()->all();
    }
    protected function attemptLogin($request): bool
    {
        return Auth::attempt($request->only('email', 'password'));
    }
    public function login($request)
    {
        $errors = $this->validator($request,$this->rule());
        if($errors){
            return [
                'message' => $errors,
                'data' => null,
                'status_number' => 404,
                'error' => true,
            ];

        }elseif(!$this->attemptLogin($request)){
            return [
                'message' => 'Unauthenticated',
                'data' => null,
                'status_number' => 401,
                'error' => true,
            ];
            $response->message('Unauthenticated')->status(401);
        }else{
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken($user->email)->plainTextToken;

            return [
                'message' => 'Login successfully',
                'data' => [
                    'name' => $user['name'],
                    'user_name' => $user['user_name'],
                    'email' => $user['email'],
                    'token' => $token,
                ],
                'status_number' => 200,
                'error' => false,
            ];
        }

        return $response;
    }


}
