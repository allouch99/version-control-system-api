<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Http\Request;


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


    public function update(Request $request, Group $group)
    {
        return  $this->groupService->update($request ,$group)->jsonResponse();
    }

    public function destroy(Group $group)
    {
        return  $this->groupService->destroy($group)->jsonResponse();
    }
    
}
