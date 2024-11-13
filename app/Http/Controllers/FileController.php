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


    public function store(Request $request,Group $group)
    {
        $errors = Validator::make($request->all(), $this->rule())->errors()->all();
        if ($errors) {
            return $this->errorResponse($errors);
        }

        $data = null;
        try{
            $data = $this->fileService->store($request,$group);
            
        }catch(FileException $exception){
           return $exception->render($request);
        }
        
        return $this->successResponse('The file has been created successfully',$data,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        //
    }

    protected function rule(): array
    {
        return  [
            'file'=>['required','file']
        ];
    }
}
