<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Group;
use Illuminate\Support\Facades\Validator;
use App\Services\FileService;
use App\Exceptions\FileException;
class FileController extends Controller
{
    protected FileService $fileService;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index(int $group)
    {
        $groups = $this->fileService->getAllFilesInGroup($group);
        return response()->json($groups);
    }


    public function store(Request $request)
    {
        return $this->fileService->store($request)->jsonResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        return $this->fileService->update($request,$file)->jsonResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        return $this->fileService->destroy($file)->jsonResponse();
    }

    public function pull(Request $request)
    {
        return $this->fileService->pull($request)->jsonResponse();
    }

}
