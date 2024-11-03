<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            'user_name' => $request['user_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ];
    }
    public function validator($request,$rule): array
    {
        return Validator::make($request->all(), $rule)->errors()->all();
    }
    public function createAccount(Request $request): array
    {
        $data = $this->data($request);
        $errors = $this->validator($request,$this->rule());
        if($errors){
            return [
                'message' => $errors,
                'data' => null,
                'status_number' => 404,
                'error' => true,
            ];
        }
        $user = User::create($data);
        $token = $user->createToken($data['email'])->plainTextToken;

        return [
            'message' => 'Account successfully created',
            'data' => [
                'name' => $user['name'],
                'user_name' => $user['user_name'],
                'email' => $user['email'],
                'token' => $token,
            ],
            'status_number' => 201,
            'error' => false,
        ];
    }

}
