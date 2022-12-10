<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\AddGroupFileRequest;
use App\Http\Requests\File\GetGroupFileRequest;
use App\Http\Requests\Group\AddGroupRequest;
use App\Models\GroupFile;
use App\Models\User;
use App\Services\FileService;
use App\Services\GroupService;
use App\Traits\GeneralTrait;

class GroupFileController extends Controller
{
    use GeneralTrait;

    /**
     *  add file to specified group.
     *
     */
    public function addFileToGroup(AddGroupFileRequest $request)
    {
        try {
             $user = auth()->user();

            if(!FileService::checkUserIsOwner($request->file_id, $user->id))
                return $this->returnError('Can Not Add, you are not owner of file', '401');

            if(!GroupService::checkUserIsOwner($request->group_id, $user->id) && !GroupService::checkUserIsMember($request->group_id, $user->id))
                return $this->returnError('Can Not Add, you are not owner or member in group', '401');


            if(FileService::checkFileNameIsExist($request->file_id, $request->group_id))
                return $this->returnError('Can Not Add, file name already exist', '401');

            $success = FileService::addFileToGroup($request, $user);
            return $this->returnSuccessMessage('File Added To Group Successfully');
        
        } catch(Exception $e) {
            return $this->returnError('Failed Add File To Group', '5000');
        }
    }
    
    /**
     * Remove file from specified group.
     *
     */
    public function removeFileFromGroup(GetGroupFileRequest $request)
    {
        try {
            $user = auth()->user();

            if(!FileService::checkUserIsOwner($request->file_id, $user->id))
                return $this->returnError('Can Not Remove, you are not owner of file', '401');
            
            $success = FileService::removeFileFromGroup($request);
        
            return $this->returnSuccessMessage('Removerd File From Group Successfully');
        } catch(Exception $e) {
            return $this->returnError('Failed Remove File From Group', '5000');
        }
    }
}
