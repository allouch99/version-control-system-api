<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Services\FileService;
class FileController extends Controller
{
    protected FileService $fileService;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }


    public function store(Request $request)
    {
        return $this->fileService->store($request)->jsonResponse();
    }


    public function show(File $file)
    {
        return $this->fileService->show($file)->jsonResponse();
    }

    public function update(Request $request, File $file)
    {
        return $this->fileService->update($request,$file)->jsonResponse();
    }

    public function destroy(File $file)
    {
        return $this->fileService->destroy($file)->jsonResponse();
    }

    public function lock(Request $request)
    {
        return $this->fileService->lock($request)->jsonResponse();
    }
    public function unlock(Request $request)
    {
        return $this->fileService->unlock($request)->jsonResponse();
    }


}
