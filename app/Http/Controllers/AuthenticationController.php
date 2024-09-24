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
            'email'=> 'required|email',
            'password'=> 'required'
        ]);


        $user = User::where('email',$fields['email'])->first();

        if($user){
            return response()->json([
                'error'=> 'email already exist. Try another email'
            ],409);
        }
         User::create($fields);

        return ['message' => 'User registered successfully!'];
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    //manually validates if the user does not exit in your database, response code is 201
    if (!$user){
        return response()->json([
            'error'=> 'email not found, Please check your email'
        ],404);
    }
    //manually validates if the username exist but the password is wrong, response code is 201
    if(!Hash::check($request ->password,$user->password)){
        return response()->json([
            'message'=>'Wrong password, Please try again'
        ],401);
    }

    $token = $user->createToken($request->email);

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
