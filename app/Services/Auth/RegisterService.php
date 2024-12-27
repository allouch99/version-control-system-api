<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterService extends Service
{
    public function rule(): array
    {
        return  [
            'name' => ['required', 'string', 'max:50','min:2'],
            'user_name' => ['required', 'string', 'max:50','min:2','unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
    public function data(Request $request): array
    {
        return [
            'name' => $request['name'],
            'user_name' => Str::slug($request['user_name'], '-'),
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ];
    }

    public function createAccount(Request $request)
    {
        $data = $this->data($request);
        $errors = $this->validator($request,$this->rule());
        if($errors)
            return $this->responseService->message($errors)->status(404)->error(true);
        
        $user = User::create($data);
        $user['token'] = $user->createToken($data['email'])->plainTextToken;

        return $this->responseService->message('Account successfully created')->status(201)->data($user);
        
    }

}
