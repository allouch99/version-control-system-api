<?php

namespace App\Services;

use App\Models\User;
use App\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GroupService extends Service
{
    protected function rule(): array
    {
        return  [
            'name' => ['required', 'string', 'max:255'],
            'type'=>['required',  Rule::in(['public', 'private']),]
        ];
    }
    protected function data(Request $request): array
    {
        return [
            'name' => $request['name'],
            'type' => $request['type'],
        ];
    }
    protected function validator($request,$rule): array
    {
        return Validator::make($request->all(), $rule)->errors()->all();
    }
    public function store(Request $request): array
    {
        $errors = $this->validator($request,$this->rule());
        if ($errors) {
            return $this->arrayData($errors,'',404,true);
        }else{
         $group = User::find(Auth::id())->groups()->create($this->data($request));
         $data = [
             'name' => $group['name'],
             'type' => $group['type'],
         ];
         return $this->arrayData('The group has been created successfully',
             $data,201,false);

        }

    }

}
