<?php

namespace App\Services;

use App\Http\Resources\InvitationResource;
use App\Models\Group;
use App\Models\User;
use App\Models\Invitation;
use App\Models\Membership;
use App\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class InvitationService extends Service
{
    protected $user;
    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
        $this->user = User::find(Auth::id());   
    }
    public function getAllowedUsers(Group $group)
    {
        
        if ($this->user->cannot('get-users',$group)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }
    
        $invalid_ids = $this->getInvalidUsersId($group);
        $users = User::whereNotIn('id',$invalid_ids)->get();

        return $this->responseService->status(200)->data($users->pluck('email'));
    }
    protected function getInvalidUsersId(Group $group)
    {
        $recipient_id = $group->invitations->pluck('recipient_id');
        $membership_id =  $group->memberships->pluck('membership.user_id');
        $invalid_ids = collect([$recipient_id, $membership_id,[$group->user_id]])->collapse()->all();
        return $invalid_ids;
    }
    public function index()
    {
        $nvitations = [
            'sent-invitations' => InvitationResource::collection($this->user->sentInvitations()->get()),
            'received-invitations' => InvitationResource::collection( $this->user->receivedInvitations()->get())
        ];
        
        return $this->responseService->status(200)->data($nvitations);
    }
    public function store(Request $request)
    {
        
        $errors = Validator::make($request->all(),$this->rule())->errors()->all();
        if ($errors) {
            return $this->responseService->message($errors)->status(404)->error(true);
        }
        $request['recipient'] = User::where('email',$request['recipient_email'])->first();
        $request['invalid_ids']  = $this->getInvalidUsersId(Group::find($request['group_id']));
        if ($this->user->cannot('create-invitation',$request)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }


        
        $data = [
            'group_id' => $request['group_id'],
            'sent_id' =>Auth::id(),
            'recipient_id' =>  $request['recipient']->id,
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
        if ($this->user->cannot('delete',$invitation)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }
        $invitation->delete();
        return $this->responseService->message('The invitation has been deleted successfully')
            ->status(201);
    }
    public function accept(Invitation $invitation)
    {
        if ($this->user->cannot('change-status',$invitation)) {
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
        if ($this->user->cannot('change-status',$invitation)) {
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
