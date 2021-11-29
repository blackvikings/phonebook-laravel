<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'unique:users|required',
            'password' => 'required',
        ];

        $input = $request->only('name', 'email', 'password');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password)]);

        return $user;
    }

    public function login()
    {
        $user = User::where("email", request('email'))->first();
        if(!isset($user)){
            return "User Not found";
        }
        if (!Hash::check(request('password'), $user->password)) {
            return "Incorrect password";
        }
        $tokenResult = $user->createToken('User');
        $user->access_token = $tokenResult->accessToken;
        $user->token_type = 'Bearer';
        return $user;
    }
}
