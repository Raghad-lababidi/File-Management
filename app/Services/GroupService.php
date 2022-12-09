<?php

namespace App\Services;

use App\Models\Group;
use App\Models\GroupFile;
use App\Models\UserGroup;

/**
 * Class GroupService
 * @package App\Services
 */
class GroupService
{
    public static function create($request, $user)
    {
        Group::create([
        'name' => $request->name,
        'owner_id' => $user->id,
        ]);

        return [];
    }

    public static function update($request)
    {
        $group = Group::where('id', $request->group_id)->first();

        if(isset($request->name)) {
            $group->name = $request->name;
        }
        $group->save();
        
        return [];
    }

    public static function destroy($request, $user)
    {
        $group = Group::where('id', $request->group_id)->first();
        $group->delete();
        
        return [];
    }

    public static function getMembersGroup($request)
    {  
        $data = [];
        $users = UserGroup::where('group_id', $request->group_id)->with('user')->get();
        $data['members'] = $users;

        return $data;
    }

    public static function getFilesGroup($request)
    {  
        $data = [];
        $files = GroupFile::where('group_id', $request->group_id)->get();
        $data['files'] = $files;
        
        return $data;
    }

    public static function checkUserIsOwner($group_id, $user_id)
    {
        $group = Group::where('id', $group_id)->first();

        if($group->owner_id == $user_id)
            return true;
        return false;
    }

    public static function checkGroupIsEmpty($group_id)
    {
        $group_file = GroupFile::where('group_id', $group_id)->first();

        if(!isset($group_file))
            return true;
        return false;
    }

    public static function checkUserIsMember($group_id, $user_id)
    {
        $user_group = UserGroup::where('user_id', $user_id)->where('group_id', $group_id)->first();

        if(isset($user_group))
            return true;
        return false;
    }

}
