<?php

namespace App\Services;

use App\Events\Report\AppendReport;
use App\Jobs\LockFileJob;
use App\Models\User;
use App\Models\File;
use App\Models\Group;
use App\Services\Service;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;

use Illuminate\Database\Eloquent\Builder ;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FileService extends Service
{
    public function getVersions(File $file)
    {
        $user = User::find(Auth::id());
        if ($user->cannot('get-version', $file)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }
        $versions = collect() ;
        if($file->version > 0){
            for($i=0 ;$i < $file->version ;$i++)
            {  
                $versions->put( 'version-'.$i , Storage::temporaryUrl(
                    $file['directory'].'versions/'.$i.'/'.$file['name'],
                     now()->addMinutes(5))) ;
            }
        }

        return $this->responseService->data($versions)
                ->status(201);
    }
    public function setVersion(File $file,$version)
    {

        if (!is_numeric($version) || $version < 0)
            return $this->responseService->message('invalid version')->status(404)->error(true);
        $user = User::find(Auth::id());
        if ($user->cannot('set-version', $file)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }
        return $this->responseService->message('The specified version has been reverted.')
                ->status(201);
    }
    public function store(Request $request)
    {
        $errors = Validator::make($request->all(), $this->rule())->errors()->all();
        if ($errors)
            return $this->responseService->message($errors)->status(404)->error(true);

        try {
            $user = User::find(Auth::id());
            $group = Group::find($request['group_id']);
            if ($user->cannot('create-file', $group)) {
                return $this->responseService->message('unauthorized')
                    ->status(403)->error(true);
            }
    
            $file = [
                'name' => $request['file']->getClientOriginalName(),
                'contents' => $request['file'],
                'directory' => $group->filesDirectory,
            ];

            $path = $file['directory'] . $file['name'];

            if (Storage::disk('local')->exists($path)) {
                throw new Exception('The file already exists.', 404);
            }

            Storage::putFileAs($file['directory'], $file['contents'], $file['name']);

            $file = $group->files()->create($file);
            event(new AppendReport($user, $file,'create'));
            return $this->responseService->message('The file has been created successfully')
                ->status(201);
        } catch (Exception $exception) {
            return $this->responseService->message($exception->getMessage())
                ->status(500)->error(true);
        }
    }
    public function update(Request $request, File $file)
    {

        $errors = Validator::make($request->all(), [
            'file' => ['required', 'file', 'max:20480']
        ])->errors()->all();
        if ($errors)
            return $this->responseService->message($errors)->status(404)->error(true);
        $user = User::find(Auth::id());
        if ($user->cannot('update', $file) ||  $request['file']->getClientOriginalName() != $file['name']) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }

        if (!Storage::disk('local')->exists($file->path)) {
            throw new Exception('The file does not exist.', 404);
        }

        Storage::copy($file->path,$file['directory'].'versions/'.$file->version.'/'.$file['name']);
        Storage::putFileAs($file['directory'], $request['file'], $file['name']);
        
        $file->version++;
        $file->updated_at = now();
        $file->save();
        event(new AppendReport($user, $file,'update'));
        return $this->responseService->message('The file has been updated successfully')
            ->status(201);
    }
    public function destroy(File $file)
    {

        $file->delete();

        $path = $file['directory'] . $file['name'];
        Storage::delete($path);

        $file->delete();
        return $this->responseService->message('The file has been deleted successfully')
            ->status(201);
    }

    public function lock(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'files_id' => ['required', 'array']
        ])->errors()->all();
        if ($errors)
            return $this->responseService->message($errors)->status(404)->error(true);

        $files_id = array_unique($request->input('files_id'));
        $user = User::find(Auth::id());
        LockFileJob::dispatch($files_id,$user);

        return $this->responseService->message('The request is currently being processed. You will receive a notification of the result.')
            ->status(200);
    }

    public function unlock(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'files_id' => ['required', 'array']
        ])->errors()->all();
        if ($errors)
            return $this->responseService->message($errors)->status(404)->error(true);

        $user = User::find(Auth::id());
        $files_id = array_unique($request->input('files_id'));
        $invalid_ids = File::whereIn('id', $files_id)
            ->where(function (Builder $query) use($user){
                $query->where('locked_by',  '!=', $user->id)
                     ->orWhere('locked_by', null);
            })->first();


        if ($invalid_ids) {
            return $this->responseService->message('unauthorized')->status(404)->error(true);
        }

        $files = File::whereIn('id', $files_id)->get();
        foreach($files as $file){
            $file->locked_by = null;
            $file->save();
            event(new AppendReport($user, $file,'unlock'));
        }
        
        return $this->responseService->message('Files unlocked successfully')
            ->status(200);
    }


    public function show(File $file)
    {
        $user = User::find(Auth::id());
        if ($user->cannot('show', $file)) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }

        $data = [
            'name' => $file->name,
            'url' => $file->temporaryUrl
        ];
        return $this->responseService->status(200)->data($data);
    }

    protected function rule(): array
    {
        return  [
            'file' => ['required', 'file', 'max:20480'],
            'group_id' => [
                'required',
                'integer',
                'gt:0',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!Group::where('id', $value)->first()) {
                        $fail("Invalid {$attribute}.");
                    }
                },
            ],
        ];
    }
}
