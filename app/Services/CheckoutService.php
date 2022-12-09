<?php

namespace App\Services;

use App\Models\Checkout;
use App\Models\File;
use Illuminate\Support\Facades\DB;

/**
 * Class CheckoutService
 * @package App\Services
 */
class CheckoutService
{
    public static function create($request, $user)
    {
        try {
            DB::beginTransaction();

            Checkout::create([
                'file_id' => $request->file_id,
                'checkin_id' => $request->checkin_id,
                'user_id' => $user->id,
            ]);

            File::where('id', $request->file_id)->update([
                'status' => 'free',
            ]);

            DB::commit();
                
            } catch (\Exception $e) {
                DB::rollback();
                return false;
        }
            return true;
    }

}
