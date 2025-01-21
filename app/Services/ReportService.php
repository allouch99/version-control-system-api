<?php

namespace App\Services;

use App\Models\File;
use App\Models\Group;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
class ReportService extends Service
{
    public function getFileReport(File $file)
    {
        if (! Gate::allows('get-file-report', $file)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }
        if(!Storage::exists($file->report))
            return $this->responseService->message('The report does not exist.')
                ->status(403)->error(true);
        $report = Storage::temporaryUrl($file->report, now()->addMinutes(5));
        return $this->responseService->status(200)->data($report);
    }
    public function getUsersReport(Group $group)
    {
        if (! Gate::allows('get-user-report', $group)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }
        $reports = Storage::files($group->filesDirectory. 'user-reports/');
        $user_reports = collect([]);
        foreach($reports as $report){
              $user_reports->push([
                'user_name' => pathinfo($report , PATHINFO_FILENAME),
                'report_url' =>  Storage::temporaryUrl($report, now()->addMinutes(5))
            ]);
        }

        return $this->responseService->status(200)->data($user_reports);
    }
   

}
