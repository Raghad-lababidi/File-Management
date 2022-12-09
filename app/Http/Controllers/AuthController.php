<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use GeneralTrait;

    public function register(RegisterRequest $request) 
    {
        $date = [];

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_name' => $request->name.User::max('id').random_int(9,9999)
        ]);

        $token = $user->createToken('token-name')->plainTextToken;

        $date['user'] = $user;
        $date['token'] = $token;

        return $this->returnData("Register Successfully", "data", $date);
    }

    public function login(LoginRequest $request) 
    {
        $data = [];

        $fieldsType = filter_var($request->login_id,FILTER_VALIDATE_EMAIL)? 'email' : 'user_name';

        if($fieldsType == 'email') {
            $fields = $request ->validate([
                'login_id' => 'required|string|email|exists:users,email',
                'password' => 'required|string'
            ]);
        }
        else {
            $fields = $request ->validate([
                'login_id' => 'required|string|exists:users,user_name',
                'password' => 'required|string'
            ]);
        }

        //check email or user_name
        $user = User::where($fieldsType,$fields['login_id'])->first();

        //check password 
        if(!$user || !Hash::check($fields['password'], $user->password)) {
           return $this->returnError("bad creds", '401');
        }

        $token = $user->createToken('token-name')->plainTextToken;

        $data = [
            'user' => $user,
            'token' => $token
        ];

        return $this->returnData("login successfuly", "success", $data);
    }


    public function logout(Request $request) 
    {
        auth()->user()->tokens()->delete();

        return $this->returnSuccessMessage("logged out");
    }
}
