<?php

namespace App\Services;

use App\Models\Checkin;
use App\Models\File;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\assertIsNotArray;

/**
 * Class CheckinService
 * @package App\Services
 */
class CheckinService
{
    public static function checkinInfo($request)
    {
        $data = [];

        $file = File::find($request->file_id);

        $checkin_info = $file->checkins()
        ->join('users','users.id','=','checkins.user_id')
        ->select('users.name as user_name','checkins.created_at')
        ->latest()->first();
        
        $data['information'] = $checkin_info;
        return $checkin_info;
    }

    public static function create($request, $user)
    {
        try {
            DB::beginTransaction();

            $checkin_type = "single_checkin";

            if(count($request->file_ids) > 1) {
                $checkin_type = "bulk_checkin";
            }

            $checkin = Checkin::create([
                'user_id' => $user->id,
                'type' => $checkin_type,
            ]);

            $checkin->files()->syncWithoutDetaching($request->file_ids);

            foreach($request->file_ids as $file_id) {
                File::where('id', $file_id)->update([
                    'status' => 'restricted',
                ]);
            }

            DB::commit();
                
            } catch (\Exception $e) {
                DB::rollback();
                return false;
        }
            return true;
    }

    public static function checkFileIsRestricted($file_ids)
    {
        if( is_array($file_ids)) {
            foreach($file_ids as $file_id) {
                $file = File::where('id', $file_id)->first();
                if($file->status == 'restricted')
                    return true;
            }
        }
        else {
            $file = File::where('id', $file_ids)->first();
            if($file->status == 'restricted')
                return true;
        }
        
        return false;
    }

}
