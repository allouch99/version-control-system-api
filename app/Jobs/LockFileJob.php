<?php

namespace App\Jobs;

use App\Events\Report\AppendReport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;
use App\Models\File;
use App\Models\User;
use App\Notifications\FileLocked;
use App\Notifications\FileStatus;

class LockFileJob implements ShouldQueue
{
    use Queueable;

    protected array $files_id;
    protected User $user;
    public function __construct(array $files_id,User $user)
    {
        $this->files_id = $files_id;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $group = File::whereIn('id', $this->files_id)->first()->group;
        $files_in_group = $group->files->pluck('id');
        if (File::whereIn('id', $this->files_id)->whereNotNull('locked_by')->first()) 
        {
            $this->user->notify(new FileLocked('Error locking files. Make sure all selected files are free.'));
        } elseif (File::whereNotIn('id',$files_in_group)->whereIn('id',$this->files_id)->first() )
        {
            $this->user->notify(new FileLocked('Error locking files. Make sure all selected files belong to the same group.'));
        }else {

            $files = File::whereIn('id', $this->files_id)->get();
            foreach($files as $file){
                $file->locked_by = $this->user->id;
                $file->save();
                event(new AppendReport($this->user, $file,'lock'));
            }
            $files_name = File::whereIn('id', $this->files_id)->get()->implode('name', ', ');
            $message = 'User : [ '. $this->user->user_name.' ] locked the following files : ' .$files_name;
            $this->user->notify(new FileLocked('Files locked successfully : '.$files_name));
            $group->user->notify(new FileLocked($message));
            Notification::send($group->memberships, new FileLocked($message));
           
        }

        
    }
}
