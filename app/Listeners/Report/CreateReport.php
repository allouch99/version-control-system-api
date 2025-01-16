<?php

namespace App\Listeners\Report;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Report\CreateReport as EventsCreateReport;
use Illuminate\Support\Facades\Storage;
class CreateReport
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
    public function handle(EventsCreateReport $event): void
    {
        $file_report_name = pathinfo($event->file->name, PATHINFO_FILENAME).'.log';
        $user_report_name = $event->user->user_name.'.log';
        $file_directory = $event->file->directory .'file-reports/';
        $user_directory = $event->file->directory .'user-reports/';
        Storage::makeDirectory($file_directory);
        Storage::makeDirectory($user_directory);

        $file_report_contents = 'The file was created by the user: [ '.$event->user->name .' ] at the time : ' . now();
        $user_report_contents = 'User : [ '.$event->user->name .' ] created the file:'.$event->file->name.' at the time : ' . now();
        Storage::append($file_directory . $file_report_name,$file_report_contents);
        Storage::append($user_directory . $user_report_name,$user_report_contents);
        
    }
}
