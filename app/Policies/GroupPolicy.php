<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    // public function createFile(User $user, Group $group): bool
    // {

    //     return (
    //         $group->type === 'public'||
    //         $group->user_id === $user->id ||
    //         $group->memberships()->where('user_id',$user->id)->wherePivot('role','writer')->first()
    //     );

    // }
    public function view(User $user, Group $group): bool
    {
        return (
            $group->type === 'public'||
            $group->user_id === $user->id ||
            $group->memberships()->where('user_id',$user->id)->first()
        ); 
    }
    public function update(User $user, Group $group): bool
    {
        return ($group->user_id === $user->id); 
    }
    public function delete(User $user, Group $group): bool
    {
        return ($group->user_id === $user->id); 
    }



}
