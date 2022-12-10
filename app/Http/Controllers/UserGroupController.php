<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\AddUserGroupRequest;
use App\Http\Requests\User\GetUserGroupRequest;
use App\Services\GroupService;
use App\Services\UserService;
use App\Traits\GeneralTrait;

class UserGroupController extends Controller
{
    use GeneralTrait;

    /**
     * Add user to specific group.
     *
     */
    public function addUserToGroup(AddUserGroupRequest $request)
    {
        try {
            $user = auth()->user();

            if(!GroupService::checkUserIsOwner($request->group_id, $user->id))
                return $this->returnError('Can Not Add, you are not owner of group', '401');

            $success = UserService::addUserToGroup($request);
        
            return $this->returnSuccessMessage('Add User To Group Successfully');
        } catch(Exception $e) {
            return $this->returnError('Failed Add User To Group', '5000');
        }
    }

    /**
     * Remove user from specific group.
     *
     */
    public function removeUserFromGroup(GetUserGroupRequest $request)
    {
        try {
            $user = auth()->user();

            if(!GroupService::checkUserIsOwner($request->group_id, $user->id))
                return $this->returnError('Can Not remove, you are not owner of group', '401');

            $success = UserService::removeUserFromGroup($request);
        
            return $this->returnSuccessMessage('Removed User From Group Successfully');
        } catch(Exception $e) {
            return $this->returnError('Failed Remove User From Group', '5000');
        }
    }
}
