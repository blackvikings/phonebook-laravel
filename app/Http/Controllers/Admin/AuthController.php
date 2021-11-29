<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email'    => 'unique:administrators|required',
            'password' => 'required',
        ];

        $input     = $request->only('name', 'email','password');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        $user = Administrator::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password)]);

        return $user;
    }

    public function login()
    {
        $user = Administrator::where("email", request('email'))->first();
        if(!isset($user)){
            return "Admin Not found";
        }
        if (!Hash::check(request('password'), $user->password)) {
            return "Incorrect password";
        }
        $tokenResult = $user->createToken('Admin');
        $user->access_token = $tokenResult->accessToken;
        $user->token_type = 'Bearer';
        return $user;
    }
}
