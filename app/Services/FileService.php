<?php

namespace App\Services;

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
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Validator;

class FileService extends Service
{
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
            $group->files()->create($file);

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
        if ($user->cannot('update', $file , $request['file']->getClientOriginalName())) {
            return $this->responseService->message('unauthorized')
                ->status(403)->error(true);
        }

        if (!Storage::disk('local')->exists($file->path)) {
            throw new Exception('The file does not exist.', 404);
        }
        
        Storage::putFileAs($file['directory'], $request['file'], $file['name']);



        $file->updated_at = now();
        $file->save();

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
            'files_id' => ['required','regex:/^(\d+)\s*(,\s*(\d+)\s*)*$/i']
        ])->errors()->all();
        if ($errors)
            return $this->responseService->message($errors)->status(404)->error(true);

        $files_id = array_unique(explode(',', $request->input('files_id')));

        LockFileJob::dispatch($files_id,Auth::id());


        return $this->responseService->message('The request is currently being processed. You will receive a notification of the result.')
            ->status(200);
    }

    public function unlock(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'files_id' => ['required','regex:/^(\d+)\s*(,\s*(\d+)\s*)*$/i']
        ])->errors()->all();
        if ($errors)
            return $this->responseService->message($errors)->status(404)->error(true);

        $files_id = array_unique(explode(',', $request->input('files_id')));
        if (File::whereIn('id', $files_id)
            ->where(function (Builder $query) {
                        $query->where('locked_by','!=',Auth::id())
                            ->orWhere('locked_by',null);
                            })->first()
                            ) {
            return $this->responseService->message('unauthorized')->status(404)->error(true);
        }

        File::whereIn('id', $files_id)->update(['locked_by' => null]);

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
            'group_id' => [ 'required', 'integer', 'gt:0',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!Group::where('id', $value)->first()) {
                        $fail("Invalid {$attribute}.");
                    }
                },
            ],
        ];
    }
}
