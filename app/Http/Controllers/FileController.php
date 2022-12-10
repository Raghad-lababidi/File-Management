<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\AddFileRequest;
use App\Http\Requests\File\GetFileRequest;
use App\Http\Requests\File\UpdateFileRequest;
use App\Models\User;
use App\Services\FileService;
use App\Services\UserService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Response;

class FileController extends Controller
{
    use GeneralTrait;

    /**
     * Creating a new file.
     *
     */
    public function create(AddFileRequest $request)
    {
        try {
            $user = auth()->user();

            $request = FileService::create($request, $user);

            return $this->returnSuccessMessage('File Created Successfully');
        } catch(Exception $e) {
            return $this->returnError('Failed Create File', '5000');
        }
    }

    /**
     * Update the specified file in storage.
     *
     */
    public function update(UpdateFileRequest $request)
    {
        try {
            $user = auth()->user();

            if(!FileService::checkUserIsOwner($request->file_id, $user->id)) {
                return $this->returnError('Failed Update File, you are not the owner of file', '5000');
            }

            $success = FileService::update($request, $user);

            return $this->returnSuccessMessage('File Updated Successfully');
        } catch(Exception $e) {
            return $this->returnError('Failed Update File', '5000');
        }
    }

    /**
     * Remove the specified file from storage.
     *
     */
    public function destroy(GetFileRequest $request)
    {
        try {
            $user = auth()->user();

            if(!FileService::checkUserIsOwner($request->file_id, $user->id)) 
                return $this->returnError('Failed Delete File, you can not delete this file you are not the owner', '401');

            $success = FileService::destroy($request);
            return $this->returnSuccessMessage('File Deleted Successfully');
            
        } catch(Exception $e) {
            return $this->returnError('Failed Delete File', '5000');
        }
    }

    /**
     * User show all his files.
     *
     */
    public function userShowOwnerFiles()
    {
        try {
            $user = auth()->user();
            $success = UserService::userShowOwnerFiles($user);

            return $this->returnData('Get Files Successfully', 'data', $success);
        } catch(Exception $e) {
            return $this->returnError('Failed Get Data', '5000');
        }
    }

    /**
     * User show file status.
     *
     */
    public function GetFileStatus(GetFileRequest $request)
    {
        try {
            $success = FileService::GetFileStatus($request);

            return $this->returnData('Get Status Successfully', 'status', $success);
        } catch(Exception $e) {
            return $this->returnError('Failed Get Status', '5000');
        }
    }

    
}
