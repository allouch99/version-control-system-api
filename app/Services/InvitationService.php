<?php

namespace App\Services;

use App\Models\User;
use App\Models\Group;
use App\Models\Invitation;
use App\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Closure;

class InvitationService extends Service
{
    public function index(string $type)
    {
        $user = User::find(Auth::id());
    
        if($type == 'sent'){
            $nvitations = $user->setInvitations()->get();
        }else if($type == 'received'){
            $nvitations = $user->invitations()->get();
        }else{
            $nvitations = $user->invitations()->get();
        }
        return $this->responseService->status(200)->data($nvitations);
    }
    public function store(Request $request)
    {
        
        $errors = Validator::make($request->all(),$this->rule())->errors()->all();
        if ($errors) {
            return $this->responseService->message($errors)->status(404)->error(true);
        }
        $data = [
            'group_id' => $request['group_id'],
            'sender_id' =>Auth::id(),
            'recipient_id' => $request['recipient_id'],
            'role' => $request['role'],
            'description' => $request['description'],
        ];
        Invitation::create($data);
        
        return $this->responseService->message('The group has been created successfully')
            ->status(201)->data($data);
    }
  

    protected function rule(): array
    {
        return  [
            'group_id' => ['required', 'integer','gt:0',
            function (string $attribute, mixed $value, Closure $fail) {
                if (!Auth::user()->groups->where('id', $value)->first()) {
                    $fail("The specified {$attribute} is invalid.");
                }
            }, ],
            'recipient_id' => ['required', 'integer','gt:0'],
            'role'=>['required',  Rule::in(['viewer', 'writer']),],
            'description' => ['required', 'string', 'max:2048']
        ];
    }

}
