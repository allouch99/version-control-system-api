<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ReportService;
class ReportController extends Controller
{
    protected ReportService $reportService;
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }
    public function getFileReport(File $file)
    {
        return  $this->reportService->getFileReport($file)->jsonResponse();
    }
    public function getUserReport(User $user,Group $group)
    {
        return  $this->reportService->getUserReport($user,$group)->jsonResponse();
    }
}
