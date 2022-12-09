<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GroupFileController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\Filecontroller;
use App\Http\Controllers\Groupcontroller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register',[AuthController::class,'register']); 
Route::post('/login',[AuthController::class,'login']); 

Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::post('/logout',[AuthController::class,'logout']); 

    Route::get('/files',[FileController::class,'userShowOwnerFiles']); 
    Route::get('/groups',[GroupController::class,'userShowOwnerGroups']); 

    // Group
    Route::post('/group/create',[GroupController::class,'create']); 
    Route::post('/group/update',[GroupController::class,'update']); 
    Route::post('/group/delete',[GroupController::class,'destroy']); 
    
    Route::get('/group/members',[GroupController::class,'getMembersGroup']); 
    Route::get('/group/files',[GroupController::class,'getFilesGroup']); 

    
    // File
    Route::post('/file/create',[FileController::class,'create']); 
    Route::post('/file/update',[FileController::class,'update']); 
    Route::post('/file/delete',[FileController::class,'destroy']); 

    Route::get('/file/status',[FileController::class,'GetFileStatus']); 
    Route::get('/file/checkin-info',[CheckinController::class,'checkinInfo']); 

    Route::post('/file/checkin',[CheckinController::class,'create']); 
    Route::post('/file/checkout',[CheckoutController::class,'create']); 
    
    Route::post('/file/add-to-group',[GroupFileController::class,'addFileToGroup']); 
    Route::post('/file/remove-from-group',[GroupFileController::class,'removeFileFromGroup']); 

    // User
    Route::post('/user/add-to-group',[UserGroupController::class,'addUserToGroup']); 
    Route::post('/user/remove-from-group',[UserGroupController::class,'removeUserFromGroup']); 
});

