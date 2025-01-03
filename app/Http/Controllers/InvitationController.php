<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use App\Services\InvitationService;

class InvitationController extends Controller
{
    protected InvitationService $invitationService;
    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }
    public function index()
    {
        return  $this->invitationService->index()->jsonResponse();
    }
    public function store(Request $request)
    {
        return  $this->invitationService->store($request)->jsonResponse();
    }
    public function accept(Invitation $invitation)
    {
        return  $this->invitationService->accept($invitation)->jsonResponse();
    }
    public function reject(Invitation $invitation)
    {
        return  $this->invitationService->reject($invitation)->jsonResponse();
    }
    public function destroy(Invitation $invitation)
    {
        return  $this->invitationService->destroy($invitation)->jsonResponse();
    }
}
