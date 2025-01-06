<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;
use App\Models\File;
use App\Models\User;
use App\Notifications\FileLocked;

class LockFileJob implements ShouldQueue
{
    use Queueable;

    protected array $files_id;
    protected int $user_id;
    public function __construct(array $files_id,int $user_id)
    {
        $this->files_id = $files_id;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!File::whereIn('id', $this->files_id)->whereNotNull('locked_by')->first()) {
            File::whereIn('id', $this->files_id)->update(['locked_by' => $this->user_id]);
            //$file = File::whereIn('id', $this->files_id)->first();
            //$users = User::where('id','<>','2')->get();
        }
        $users = User::where('id','<>','2')->get();
        Notification::send($users, new FileLocked('the file lock suc'));
        
    }
}
