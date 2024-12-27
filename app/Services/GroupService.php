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
        $groups = Group::where('type','public')->get();
        return $this->responseService->data($groups);
    }

    public function store(Request $request)
    {

        $errors = Validator::make($request->all(),$this->rule())->errors()->all();
        if ($errors) {
            return $this->responseService->message($errors)->status(404)->error(true);
        }
        $user = User::find(Auth::id());
        $path = 'images/'.$user['user_name'].'/'.Str::slug($request['name'], '-');
        $data = [
            'name' => Str::slug($request['name'], '-'),
            'type' => $request['type'],
            'description' => $request['description'],
           ];
        if($request->hasFile('bg_image')){
            $data['bg_image_url'] = Storage::put($path, $request['bg_image']);
        }
        if($request->hasFile('icon_image')){
            $data['icon_image_url'] = Storage::put($path, $request['icon_image']);
        }  
        


        $user->groups()->create($data);
        return $this->responseService->message('The group has been created successfully')
            ->status(201)->data($data);


    }
    public function update(Request $request,Group $group)
    {
        //
    }
    public function show(Group $group)
    {
        
        $group['bg_image_url']= Storage::temporaryUrl(
            $group['bg_image_url'], now()->addMinutes(5)
        );
        $group['icon_image_url']= Storage::temporaryUrl(
            $group['icon_image_url'], now()->addMinutes(5)
        );
        return $this->responseService->data($group);
    }
    public function destroy(Group $group)
    {
        $group->delete();
    }

    protected function rule(): array
    {
        return  [
            'name' => ['required', 'string', 'max:255',
            function (string $attribute, mixed $value, Closure $fail) {
                if (Auth::user()->groups->where($attribute, $value)->first()) {
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
