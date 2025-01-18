<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class InvitationPolicy
{


    public function getUsers(User $user ,Group $group): bool
    {
        if (!$user->groups->where('id',$group['id'])->first())
            return false;

        return true;
    }
    public function create(User $user ,Request $request): bool
    {
        if(!$request['recipient'])
            return false;
        if (!$user->groups->where('id',$request['group_id'])->first())
            return false;
        if(collect( $request['invalid_ids'])->contains($request['recipient']->id))
            return false;
        
        return true;
    }


    public function delete(User $user, Invitation $invitation): bool
    {
        
        if ($invitation->sentUser->id != $user->id || $invitation->status != 'unread')
            return false;

        return true;
    }
    public function changeStatus(User $user, Invitation $invitation): bool
    {
        
        if ($invitation->receivedUser->id != $user->id || $invitation->status != 'unread')
            return false;

        return true;
    }

}
