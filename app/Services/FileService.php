<?php

namespace App\Services;

use App\Models\Checkin;
use App\Models\File;
use App\Models\FileCheckin;
use App\Models\GroupFile;
use App\Models\Modification;

use DB;
use Dotenv\Store\FileStore;

/**
 * Class FileService
 * @package App\Services
 */
class FileService
{
    
    public static function create($request, $user)
    {
        $file = FileMangeService::uploadFile($request->file, $user->name);

        File::create([
            'name' => $request->name,
            'path' => $file,
            'status' => 'free',
            'owner_id' => $user->id,
        ]);

        return [];
    }

    public static function update($request, $user)
    {
        try {
            DB::beginTransaction();

            $old_file = File::where('id', $request->file_id)->first();
    
            if (isset($request->file)) {
                $new_file = FileMangeService::updateFile($request->file, $old_file->path);
                $old_file->path = $new_file;
            }
    
            if (isset($request->new_name)) {
                $old_file->name = $request->new_name;
            }

            $old_file->save();

            Modification::create([
                'user_id' => $user->id,
                'file_id' => $request->file_id,
            ]);

            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollback();
            return false;
       }
        return true;
    }

    public static function destroy($request)
    {
        $file = File::where('id', $request->file_id)->first();
        FileMangeService::deleteFile($file->path);
        $file->delete();

        return [];                   
    }

    public static function addFileToGroup($request)
    {
        GroupFile::create([
            'group_id' => $request->group_id,
            'file_id' => $request->file_id,
        ]);

            return [];
    }


    public static function removeFileFromGroup($request)
    {
        $group_file = GroupFile::where('file_id', $request->file_id)->where('group_id', $request->group_id);
        $group_file->delete();
        return [];
    }

    public static function GetFileStatus($request)
    {
        $file = File::where('id', $request->file_id)->select('name', 'status')->first();
        return $file;
    }

    public static function checkFileNameIsExist($file_id, $group_id)
    {
        $file = File::where('id', $file_id)->first();
        $files = GroupFile::where('group_id', $group_id)->with('file')->get();

       foreach($files as $one) {
          if($one->file->name == $file->name);
            return true;
       }
       return false;
    }

    public static function checkUserIsOwner($file_id, $user_id)
    {
        $file = File::where('id', $file_id)->first();

        if($file->owner_id == $user_id)
            return true;
        return false;
    }

    public static function checkUserCheckinFile($file_id, $user_id)
    {
        $file_checkin = FileCheckin::where('file_id', $file_id)->latest()->first();;

        $checkin = Checkin::where('id',  $file_checkin->checkin_id)->first();

        if($checkin->user_id == $user_id)
            return true;
        return false;
    }

}
