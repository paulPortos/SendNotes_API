<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Admin;

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
    //old login
/*
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
*/


    /*public function login(Request $request)
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
*/

        // Check for admin credentials first
        public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Check for admin credentials
        if ($request->username === 'admin' && $request->password === 'admin123') {
            $admin = Admin::firstOrCreate([
                'username' => 'admin'
            ], [
                'password' => Hash::make('admin123')
            ]);

            $token = $admin->createToken('Admin Token')->plainTextToken;

            return [
                'admin' => $admin,
                'token' => $token
            ];
        }

        // Check for user credentials
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json([
                'error' => 'User not found. Please check your username.'
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Wrong password. Please try again.'
            ], 401);
        }

        $token = $user->createToken($request->username)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
    

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
    
        if ($token) {
            
            $user = $request->user();
            if ($user) {
                $user->tokens()->delete();
                return response()->json(['message' => 'User has been logged out.']);
            }
    
           
            $admin = $request->admin();
            if ($admin) {
                $admin->tokens()->delete();
                return response()->json(['message' => 'Admin has been logged out.']);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        
        return response()->json(['error' => 'No token provided'], 401);
    }
}
