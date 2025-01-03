<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class InvitationPolicy
{


    public function create(User $user ,Request $request): bool
    {
        if (!$user->groups->where('id',$request['group_id'])->first() || $user->id == $request['recipient_id'])
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
