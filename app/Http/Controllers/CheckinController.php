<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\GetFileRequest;
use App\Services\CheckinService;
use App\Services\FileService;
use Illuminate\Http\Request;

use App\Traits\GeneralTrait;

class CheckinController extends Controller
{
    use GeneralTrait;

    /**
     * Show checkin info.
     *
     */
    public function checkinInfo(GetFileRequest $request)
    {
        try {
            $success = CheckinService::checkinInfo($request);

            return $this->returnData('Get information Successfuly', 'data', $success);
        } catch(Exception $e) {
            return $this->returnError('Failed Get information', '5000');
        }
    }

    public function create(Request $request)
    {
        try {
            $user = auth()->user();

            if(CheckinService::checkFileIsRestricted($request->file_ids)) {
                return $this->returnError('Failed Checkin files, file restricted', '5000');
            }

            $success = CheckinService::create($request, $user);

            if($success)
                return $this->returnSuccessMessage("Checkin File Successfuly");

            return $this->returnError('Failed Checkin files', '5000');
        } catch(Exception $e) {
            return $this->returnError('Failed Checkin files', '5000');
        }
    }
}
