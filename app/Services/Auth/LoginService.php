<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Hash;

class LoginService extends Service
{
   
    public function rule(): array
    {
        return [
            'email' => ['required', 'string','email'],
            'password' => ['required', 'string'],
        ];
    }


    public function login($request)
    {
        
        $errors = $this->validator($request,$this->rule());
        if($errors)
            return $this->responseService->message($errors)->status(404)->error(true);

        $user = User::where('email', $request['email'])->first();
        if ($user && Hash::check($request['password'], $user->password))
        {
            $token = $user->createToken($user->email)->plainTextToken;
            $data = [
                'name' => $user['name'],
                'user_name' => $user['user_name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'token' => $token,
            ];
            return $this->responseService->message('Login successfully')->data($data);
        }
        return $this->responseService->message('Unauthenticated')->status(401)->error(true);
    }



}
