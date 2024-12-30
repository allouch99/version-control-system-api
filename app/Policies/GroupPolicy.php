<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    public function createFile(User $user, Group $group): bool
    {
        if($group->user_id === $user->id)
            return true;
        return false;
    }
    public function pullFile(User $user, Group $group): bool
    {
        if($group->user_id === $user->id)
            return true;
        return false;
    }

}
