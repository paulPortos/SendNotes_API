<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        // Validate the incoming request fields
        // 'username' must be provided and cannot exceed 100 characters
        // 'email' must be a valid email format
        // 'password' is required (no specific validation rules for length or format)
        try {
            // Validate the request
            $fields = $request->validate([
                'username' => 'required|max:100',
                'email'=> 'required|email',
                'password'=> 'required'
            ]);

             // Check if a user already exists with the provided email
        // The `where` method queries the User model for the first record matching the email
        $user = User::where('email', $fields['email'])->first();

        // If the user is found (i.e., an account with the same email exists), return a conflict (409) error response
        if ($user) {
            return response()->json([
                'error' => 'email already exist. Try another email'
            ], 409);
        }

        // If no user exists with the given email, create a new user using the validated fields
        User::create($fields);

        // Return a success message once the user is registered
        return ['message' => 'User registered successfully!',201];



        } catch (ValidationException $e) {
            // Return JSON response with validation errors
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function login(Request $request)
    {
        // Validate the incoming request data
        // Both 'email' and 'password' fields are required for login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Fetch the user from the database using the provided email
        // It looks for the first record where the email matches
        $user = User::where('email', $request->email)->first();

        // Check if the user does not exist in the database
        // If no user is found, return a 404 response with an error message
        if (!$user) {
            return response()->json([
                'error' => 'email not found, Please check your email'
            ], 404);
        }

        // Check if the password provided does not match the stored hashed password
        // If the password is incorrect, return a 401 Unauthorized response with an error message
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Wrong password, Please try again'
            ], 401);
        }

        // Create a personal access token for the authenticated user
        // The token is generated using the user's email
        $token = $user->createToken($request->email);

        // Return the user information along with the plain text version of the generated token
        return [
            'user' => $user,              // User data is returned
            'token' => $token->plainTextToken  // Generated token in plain text format
        ];
    }

    public function logout(Request $request)
    {
        // Get the authenticated user and revoke (delete) all of their active tokens
        // This effectively logs the user out by invalidating all tokens
        $request->user()->tokens()->delete();

        // Return a response message indicating the user has successfully logged out
        return [
            'message' => 'User has been logged out.'
        ];
    }

}
