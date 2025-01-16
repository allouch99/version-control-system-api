<?php

namespace App\Listeners\Report;

use App\Events\Report\UpdateReport as ReportUpdateReport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateReport
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
    public function handle(ReportUpdateReport $event): void
    {
        //
    }
}
