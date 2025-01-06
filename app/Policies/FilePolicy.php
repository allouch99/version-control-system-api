<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use App\Models\Group;
use Illuminate\Auth\Access\Response;

class FilePolicy
{
    public function create(User $user, Group $group): bool
    {
        return (
            $group->type === 'public'||
            $group->user_id === $user->id ||
            $group->memberships()->where('user_id',$user->id)->wherePivot('role','writer')->first()
        );

    }
    public function show(User $user, File $file): bool
    {
        return ( $user->id == $file->locked_by);
    }
    public function update(User $user, File $file , string $file_updated_name): bool
    {
        return ( $user->id == $file->locked_by && $file_updated_name === $file['name']);
    }
}
