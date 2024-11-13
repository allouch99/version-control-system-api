<?php

namespace App\Services;

use App\Models\User;
use App\Models\File;
use App\Models\Group;
use App\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\FileException;

class FileService extends Service
{
    public function getAllFilesInGroup(int $group)
    {
        return Group::find($group)->files;
    }

    public function store(Request $request,Group $group)
    {
        $user = User::find(Auth::id());
        $path = 'files/'.$user['user_name'].'/'.$group['name'];

        $data = [
            'name' => $request['file']->getClientOriginalName(),
            'file' => $request['file'],
            'file_path' => $path.'/'. $request['file']->getClientOriginalName(),
        ];

        if (Storage::disk('local')->exists($data['file_path'])) {
            throw new FileException('The file already exists.', 404);
            return $data;
        }

        Storage::putFileAs($path, $data['file'], $data['name']);
        $group->files()->create($data);

        return $data;

    }
    public function update(Request $request,File $file)
    {
        $data = [
            'name' => $request['name'],
            'type' => $request['type'],
           ];
        
        $file->update($data);
        return [
            'name' => $file['name'],
            'type' => $file['type'],
        ];
    }
    public function destroy(File $file)
    {
        $file->delete();
    }

}
