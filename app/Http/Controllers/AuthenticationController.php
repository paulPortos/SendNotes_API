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
            'username' => 'required|max:100|Unique:users',
            'password'=> 'required',
        ]);

         User::create($fields);


        return ['message' => 'User registered successfully!'];
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users',
            'password'=> 'required',
        ]);

        $user = User::where('username', $request->username)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return [
                'message' => 'Logged in unsuccessfully'
            ];
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
