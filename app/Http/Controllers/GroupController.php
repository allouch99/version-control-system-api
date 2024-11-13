<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Closure;

class GroupController extends Controller
{
    protected GroupService $groupService;
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }
    public function index()
    {
        $groups = $this->groupService->getAllGroups();
        return $this->successResponse('List of groups',$groups,200);
    }

    public function store(Request $request): JsonResponse
    {
        
        $errors = Validator::make($request->all(), $this->rule())->errors()->all();
        if ($errors) {
            return $this->errorResponse($errors);
        }
        $data = $this->groupService->store($request);
        return $this->successResponse('The group has been created successfully',$data,201);
       
    }


    public function show(Group $group)
    {
        return response()->json($group);
        return $this->successResponse('',$group,200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $errors = Validator::make($request->all(), $this->rule())->errors()->all();
        if ($errors) {
            return $this->errorResponse($errors);
        }
       // $data = $this->groupService->update($request,$id);
        //return $this->successResponse('The group has been updated successfully',$data,201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        $this->groupService->destroy($group);
        return $this->successResponse('The group has been deleted successfully',[],204);
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
            'type'=>['required',  Rule::in(['public', 'private']),]
        ];
    }
}
