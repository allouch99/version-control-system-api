<?php

namespace App\Listeners\Report;

use App\Events\Report\AppendReport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class UserReport
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AppendReport $event): void
    {

        $user_report_name = $event->user->user_name.'.log';
        $user_directory = $event->file->directory .'user-reports/';
        Storage::makeDirectory($user_directory);

        $user_report_contents = 'User: [ '.$event->user->user_name.' ] Process: [ '.$event->process.' ] File [ '.$event->file->name.'] Time : ['.now().' ]';
        Storage::append($user_directory . $user_report_name,$user_report_contents);
    }
}
