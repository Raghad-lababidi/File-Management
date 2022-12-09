<?php

namespace App\Services;

use App\Models\UserGroup;
use App\Repositories\UserRepository;
/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
    public static function removeUserFromGroup($request)
    {
        $userRepository = new UserRepository;
        $user_group = $userRepository->UserGroup($request->user_id,$request->group_id);
        $user_group->delete();

        return [];
    }

    public static function addUserToGroup($request)
    {  
        $userRepository = new UserRepository;
        $userRepository->addToGroup($request->user_id,$request->group_id);
        
        return [];
    }

    public static function userShowOwnerFiles($user)
    {
        $data = [];
        $files = $user->files()->select('name','status','created_at')->get();
        $data['my_files'] = $files ;

        return  $data;
    }

    public static function userShowOwnerGroups($user)
    {
        $data = [];
        $groups = $user->ownerGroups()->select('name','created_at')->get();
        $data['my_groups'] = $groups ;
        
        return $data;
    }
    
}
