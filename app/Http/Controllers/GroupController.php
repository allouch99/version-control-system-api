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
        return  $this->groupService->index()->jsonResponse();
    }

    public function store(Request $request)
    {
        return  $this->groupService->store($request)->jsonResponse();
    }


    public function show(Group $group)
    {
        return  $this->groupService->show($group)->jsonResponse();

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        return  $this->groupService->destroy($group)->jsonResponse();
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
