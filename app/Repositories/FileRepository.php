<?php

namespace App\Repositories;

use App\Models\File;


class FileRepository
{
    
    public function create($request){

        File::create([
            'name' => $request->name,
            'path' => $file,
            'status' => 'free',
            'owner_id' => $user->id,
        ]);
    }

    
}