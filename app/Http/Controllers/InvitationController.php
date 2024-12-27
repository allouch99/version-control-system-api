<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InvitationService;

class InvitationController extends Controller
{
    protected InvitationService $invitationService;
    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }
    public function index(string $type = 'all')
    {
        return  $this->invitationService->index($type)->jsonResponse();
    }
    public function store(Request $request)
    {
        return  $this->invitationService->store($request)->jsonResponse();
    }
    public function accept(Request $request)
    {
        return  $this->invitationService->store($request)->jsonResponse();
    }
}
