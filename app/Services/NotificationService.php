<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationService extends Service
{

    public function index()
    {
        $user = User::find(Auth::id());
        return $this->responseService->status(200)->data($user->notifications);
    }
    public function unread()
    {
        $user = User::find(Auth::id());
        return $this->responseService->status(200)->data($user->unreadNotifications);
    }
    public function markAsRead()
    {
        $user = User::find(Auth::id());
        $user->unreadNotifications->markAsRead();
        return $this->responseService->status(200)->message('Notifications updated successfully');
    }

}