<?php

namespace App\Http\Controllers;

use App\Services\CheckinService;
use App\Services\CheckoutService;
use App\Services\FileService;
use Illuminate\Http\Request;

use App\Traits\GeneralTrait;

class CheckoutController extends Controller
{
    use GeneralTrait;


    public function create(Request $request)
    {
        try {
            $user = auth()->user();

            if(!CheckinService::checkFileIsRestricted($request->file_id)) {
                return $this->returnError('Failed Checkout files, file is not checked in', '5000');
            }

            if(!FileService::checkUserCheckinFile($request->file_id, $user->id)) {
                return $this->returnError('Failed Checkout files, another user checkin file', '5000');
            }

            $success = CheckoutService::create($request, $user);

            if($success)
                return $this->returnSuccessMessage("Checkout File Successfuly");

            return $this->returnError('Failed Checkout files', '5000');
        } catch(Exception $e) {
            return $this->returnError('Failed Checkout files', '5000');
        }
    }
}
