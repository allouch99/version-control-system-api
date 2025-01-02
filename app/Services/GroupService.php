<?php

namespace App\Services;

use App\Models\User;
use App\Models\Group;
use App\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Closure;

class GroupService extends Service
{
    public function index()
    {
        $user = User::find(Auth::id());
        $personalGroups = Group::Where('user_id',Auth::id())->get();
        $publicGroups = Group::where('type','public')->WhereNot('user_id',Auth::id())->get();
        $sharedGroups = $user->memberships()->get();

        $groups = [
            'personal-groups' => $personalGroups,
            'public-groups' => $publicGroups,
            'shared-groups' => $sharedGroups,
        ];
        return $this->responseService->data($groups);
    }

    public function store(Request $request)
    {
        $user = User::find(Auth::id());
        $errors = Validator::make($request->all(),$this->rule($user))->errors()->all();
        if ($errors) {
            return $this->responseService->message($errors)->status(404)->error(true);
        }
        
        $path = 'images/'.$user['user_name'].'/'.Str::slug($request['name'], '-');
        $data = [
            'id' => null,
            'name' => Str::slug($request['name'], '-'),
            'type' => $request['type'],
            'description' => $request['description'],
            'bg_image_url' => null,
            'icon_image_url'=> null
           ];
        if($request->hasFile('bg_image')){
            $data['bg_image_url'] = Storage::put($path, $request['bg_image']);
        }
        if($request->hasFile('icon_image')){
            $data['icon_image_url'] = Storage::put($path, $request['icon_image']);
        }  
        
        $group = $user->groups()->create($data);
        return $this->responseService->message('The group has been created successfully')
            ->status(201)->data($group);


    }

    public function show(Group $group)
    {
        $user = User::find(Auth::id());
        if ($user->cannot('view', $group)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }
        $data = [
            'group' => $group ,
            'files' => $group->files()->get()
        ];
        return $this->responseService->data($data);
    }
    public function update(Request $request ,Group $group)
    {
        $user = User::find(Auth::id());
        if ($user->cannot('update', $group)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }
        $errors = Validator::make($request->all(),['description'=>['nullable','string', 'max:2048']])->errors()->all();
        if ($errors) {
            return $this->responseService->message($errors)->status(404)->error(true);
        }
        if($request->has('description')){
            $group['description'] = $request['description'];
        }
        $path = 'images/'.$user['user_name'].'/'. $group['name'];
        if($request->hasFile('bg_image')){
            if($group->bgImageOriginalUrl)
                Storage::delete($group->bgImageOriginalUrl);
            $group['bg_image_url'] = Storage::put($path, $request['bg_image']);
        }
        if($request->hasFile('icon_image')){
            if($group->iconImageOriginalUrl)
                Storage::delete($group->iconImageOriginalUrl);
            $group['icon_image_url'] = Storage::put($path, $request['icon_image']);
        } 

        $group->save();
        return $this->responseService->message('The group has been updated successfully')
            ->data($group)->status(201);
    }
    public function destroy(Group $group)
    {
        $user = User::find(Auth::id());
        if ($user->cannot('delete', $group)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }
        Storage::deleteDirectory($group->filesDirectory);
        Storage::deleteDirectory($group->imagesDirectory);
        $group->delete();
        return $this->responseService->message('The group has been deleted successfully')
            ->status(204);
    }

    protected function rule(User $user): array
    {
        return  [
            'name' => ['required', 'string', 'max:255',
            function (string $attribute, mixed $value, Closure $fail) use($user) {
                if ($user->groups->where($attribute,Str::slug($value, '-') )->first()) {
                    $fail("This {$attribute} already exists.");
                }
            },    
            ],
            'type'=>['required',  Rule::in(['public', 'private']),],
            'description'=>['required', 'string', 'max:2048'],
            'bg_image'=>['file', 'image', 'max:10240'],
            'icon_image'=>['file', 'image', 'max:10240']
        ];
    }

}
