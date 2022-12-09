<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserGroup;

class UserRepository
{
    
    public function UserGroup($user_id,$group_id){

        $user_group = UserGroup::where('user_id', $user_id)->where('group_id', $group_id);
        return $user_group;
    }

    public function addToGroup($user_id,$group_id){

        UserGroup::create([
            'group_id' => $group_id,
            'user_id' => $user_id,
        ]);
        
    }
}