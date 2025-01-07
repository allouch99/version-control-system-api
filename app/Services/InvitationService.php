<?php

namespace App\Services;

use App\Models\User;
use App\Models\Invitation;
use App\Models\Membership;
use App\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InvitationService extends Service
{
    public function index()
    {
        $user = User::find(Auth::id());
        $nvitations = [
            'sent-invitations' => $user->sentInvitations()->get(),
            'received-invitations' => $user->receivedInvitations()->get()
        ];

        return $this->responseService->status(200)->data($nvitations);
    }
    public function store(Request $request)
    {
        
        $errors = Validator::make($request->all(),$this->rule())->errors()->all();
        if ($errors) {
            return $this->responseService->message($errors)->status(404)->error(true);
        }
        $user = User::find(Auth::id());
        $recipient = User::where('email',$request['recipient_email'])->first();
        if(!$recipient){
            return $this->responseService->message('The specified email is invalid.')->status(404)->error(true);
        }
        if ($user->cannot('create-invitation',$request)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }
        $data = [
            'group_id' => $request['group_id'],
            'sent_id' =>Auth::id(),
            'recipient_id' =>  $recipient->id,
            'role' => $request['role'],
            'description' => $request['description'],
        ];
        $invitation = Invitation::create($data);
        $data['recipient_email'] = $request['recipient_email'];
        return $this->responseService->message('The invitation has been created successfully')
            ->status(201)->data($invitation);
    }
  
    public function destroy(Invitation $invitation)
    {
        $user = User::find(Auth::id());
        if ($user->cannot('delete',$invitation)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }
        $invitation->delete();
        return $this->responseService->message('The invitation has been deleted successfully')
            ->status(201);
    }
    public function accept(Invitation $invitation)
    {
        $user = User::find(Auth::id());
        if ($user->cannot('change-status',$invitation)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }
        Membership::create([
            'user_id' => $invitation['recipient_id'],
            'group_id' => $invitation['group_id'],
            'role' => $invitation['role'],
        ]);
        $invitation->status = 'accepted';
        $invitation->save();
        return $this->responseService->message('The invitation has been accepted successfully')
            ->status(201);
    }
    public function reject(Invitation $invitation)
    {
        $user = User::find(Auth::id());
        if ($user->cannot('change-status',$invitation)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }
        $invitation->status = 'rejected';
        $invitation->save();
        return $this->responseService->message('The invitation has been rejected successfully')
            ->status(201);
    }
    protected function rule(): array
    {
        return  [
            'group_id' => ['required', 'integer','gt:0'],
            'recipient_email' => ['required', 'string','email'],
            'role'=>['required',  Rule::in(['viewer', 'writer']),],
            'description' => ['required', 'string', 'max:2048']
        ];
    }

}
