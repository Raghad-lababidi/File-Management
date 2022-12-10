<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\GetFileRequest;
use App\Http\Requests\Group\AddGroupRequest;
use App\Http\Requests\Group\GetGroupRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Models\User;
use App\Services\GroupService;
use App\Services\UserService;
use App\Traits\GeneralTrait;

class GroupController extends Controller
{
    use GeneralTrait;

    /**
     * Creating a new group.
     *
     */
    public function create(AddGroupRequest $request)
    {
        try {
            $user = auth()->user();
            $success = GroupService::create($request, $user);

            return $this->returnSuccessMessage('Group Created Successfully');
        } catch(Exception $e) {
            return $this->returnError('Failed Create Group', '5000');
        }
    }

    /**
     * Update the specified group in storage.
     *
     */
    public function update(UpdateGroupRequest $request)
    {
        try {
            $user = auth()->user();
            
            if(!GroupService::checkUserIsOwner($request->group_id, $user->id))
                return $this->returnError('Failed Update Group, you are not the owner', '401');
                
            $success = GroupService::update($request);

            return $this->returnSuccessMessage('Group Updated Successfully');
        } catch(Exception $e) {
            return $this->returnError('Failed Update Group', '5000');
        }
    }

    /**
     * Remove the specified group from storage.
     *
     */
    public function destroy(GetGroupRequest $request)
    {
        try {

            $user = auth()->user();

            if(!GroupService::checkUserIsOwner($request->group_id, $user->id))
                return $this->returnError('Failed Delete Group, you are not the owner', '401');

            if(!GroupService::checkGroupIsEmpty($request->group_id)) 
                return $this->returnError('Failed Delete Group, group has files', '401');

            $success = GroupService::destroy($request, $user);

            return $this->returnSuccessMessage('Group Deleted Successfully');

        } catch(Exception $e) {
            return $this->returnError('Failed Delete Group', '5000');
        }
    }

    /**
     * Get all files in specific group
     *
     */
    public function getFilesGroup(GetGroupRequest $request)
    {
        try {
            $user = auth()->user();

            if(!GroupService::checkUserIsOwner($request->group_id, $user->id) && !GroupService::checkUserIsMember($request->group_id, $user->id))
                return $this->returnError('Can Not Add, you are not owner or member in group', '401');

            $success = GroupService::getFilesGroup($request);
            
            return $this->returnData('Get Data Successfully', 'data', $success);

        } catch(Exception $e) {
            return $this->returnError('Failed Get Data', '5000');
        }
    }

    /**
     * Get all users in specific group
     *
     */
    public function getMembersGroup(GetGroupRequest $request)
    {
        try {
            $user = auth()->user();

            $success = GroupService::getMembersGroup($request);
            
            return $this->returnData('Get Members Successfully', 'data', $success);

        } catch(Exception $e) {
            return $this->returnError('Failed Get Data', '5000');
        }
    }

    /**
     * User show all his groups.
     *
     */
    public function userShowOwnerGroups()
    {
        try {
            $user = auth()->user();
            $success = UserService::userShowOwnerGroups($user);

            return $this->returnData('Get Groups Successfully', 'data', $success);
        } catch(Exception $e) {
            return $this->returnError('Failed Get Data', '5000');
        }
    }
}
