<?php

namespace App\Services;

use App\Models\User;
use App\Models\File;
use App\Models\Group;
use App\Services\Service;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

class FileService extends Service
{
    public function getAllFilesInGroup(int $group)
    {
        return Group::find($group)->files;
    }

    public function store(Request $request)
    {
        $errors = Validator::make($request->all(), [
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
        ])->errors()->all();
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
                'directory' => $path = 'files/' . $user['user_name'] . '/' . $group['name'] . '/',
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

        if (!$errors && $file['name'] != $request['file']->getClientOriginalName())
            $errors[] = 'The attached file must have the same name as the current file.';
        if ($errors)
            return $this->responseService->message($errors)->status(404)->error(true);

        $path = $file['directory'] . $file['name'];
        if (!Storage::disk('local')->exists($path)) {
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
            'files_id' => [
                'required',
                'regex:/^(\d+)\s*(,\s*(\d+)\s*)*$/i',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (File::whereIn('id', explode(',', $value))->whereNotNull('user_id')->first()) {
                        $fail("Locked files cannot be handled.");
                    }
                }
            ]
        ])->errors()->all();
        if ($errors)
            return $this->responseService->message($errors)->status(404)->error(true);
        $files_id = array_unique(explode(',', $request->input('files_id')));

        File::whereIn('id', $files_id)->update(['user_id' => Auth::id()]);



        return $this->responseService->message('Files locked successfully')
            ->status(200);
    }
    public function pull(File $file)
    {
        if (Auth::id() != $file->user_id)
            return $this->responseService->message('Unauthenticated')->status(404)->error(true);

        $data = [
            'name' => $file->name,
            'url' => $file->temporaryUrl
        ];
        return $this->responseService->status(200)->data($data);
    }
}
