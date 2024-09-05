<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|max:100',
            'password'=> 'required',
        ]);


        $user = User::where('username',$fields['username'])->first();

        if($user){
            return response()->json([
                'error'=> 'username already exist. Try another username'
            ],409);
        }
         User::create($fields);

        return ['message' => 'User registered successfully!'];
    }

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required'
    ]);

    $user = User::where('username', $request->username)->first();

    //manually validates if the user does not exit in your database, response code is 201
    if (!$user){
        return response()->json([
            'error'=> 'user not found, Please check your username'
        ],201);
    }
    //manually validates if the username exist but the password is wrong, response code is 201
    if(!Hash::check($request ->password,$user->password)){
        return response()->json([
            'message'=>'Wrong password, Please try again'
        ],201);
    }

    $token = $user->createToken($request->username);

    return [
        'user' => $user,
        'token' => $token->plainTextToken
    ];
}

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return [
            'message' => 'User has been logged out.'
        ];
    }
}
