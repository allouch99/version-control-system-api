<?php

namespace App\Services;

use App\Models\File;
use App\Models\Group;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Storage;

class ReportService extends Service
{
    public function getFileReport(File $file)
    {
        
        $report = Storage::temporaryUrl($file->report, now()->addMinutes(5));
        return $this->responseService->status(200)->data($report);
    }
    public function getUserReport(User $user,Group $group)
    {
        $report = $group->filesDirectory . 'user-reports/'. $user->user_name . '.log';
        $report = Storage::temporaryUrl($report, now()->addMinutes(5));
        return $this->responseService->status(200)->data($report);
    }
   

}
