<?php

namespace App\Listeners\Report;

use App\Events\Report\AppendReport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
class FileReport
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
        $file_report_name = pathinfo($event->file->name, PATHINFO_FILENAME).'.log';
        $file_directory = $event->file->directory .'file-reports/';
        Storage::makeDirectory($file_directory);

        $file_report_contents = 'File: [ '.$event->file->name.' ] Process: [ '.$event->process.' ] By User: [ '.$event->user->user_name.' ] Time : ['.now().' ]';
        Storage::append($file_directory . $file_report_name,$file_report_contents);
        
    }
}
