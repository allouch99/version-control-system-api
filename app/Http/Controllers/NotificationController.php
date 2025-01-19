<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index()
    {
        return  $this->notificationService->index()->jsonResponse();
    }
    public function unread()
    {
        return  $this->notificationService->unread()->jsonResponse();
    }
    public function markAsRead()
    {
        return  $this->notificationService->markAsRead()->jsonResponse();
    }

}
