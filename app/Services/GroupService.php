<?php

namespace App\Services;

use App\Models\User;
use App\Models\Group;
use App\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GroupService extends Service
{
    public function getAllGroups()
    {
        return Group::all();
    }

    public function store(Request $request): array
    {
        $user = User::find(Auth::id());
        $path = 'files/'.$user['user_name'].'/'.$request['name'];
        if($request->hasFile('bg_image')){
            $bg_image_url = Storage::store($path, $request['bg_image']);
        }
        if($request->hasFile('icon_image')){
            $icon_image_url = Storage::store($path, $request['icon_image']);
        }
        
        
       $data = [
        'name' => $request['name'],
        'type' => $request['type'],
        'description' => $request['description'],
        'bg_image_url' => $bg_image_url,
        'icon_image_url' => $icon_image_url,
       ];

       
        
        $group = $user->groups()->create($data);
        
        return [
            'name' => $group['name'],
            'description' => $group['description'],
        ];

    }
    public function update(Request $request,Group $group)
    {
        $data = [
            'name' => $request['name'],
            'type' => $request['type'],
           ];
        
        $group->update($data);
        return [
            'name' => $group['name'],
            'type' => $group['type'],
        ];
    }
    public function destroy(Group $group)
    {
        $group->delete();
    }

}
