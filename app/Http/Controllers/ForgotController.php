<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Mail\MailOtp;
use App\Mail\ResetPasswordOTP;
use App\Models\Forgot_pass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotController extends Controller
{
    public function forgot(Request $request)
    {
        // Validate the incoming request to ensure email is present and valid
        $validatedData = $request->validate([
            'email' => 'required|email|string',
        ]);
     
    
        // Find the user by email
        $user = User::where('email', $validatedData['email'])->first();
    
        // Check if user exists
        if (!$user) {
            return response()->json(['message' => 'No record found, try a different email'], 404);
        }
    
        // Generate a 4-digit random token to reset the password
        $resetPasswordToken = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    
        // Check if the user already requested a password reset
        $userPassReset = Forgot_pass::where('email', $user->email)->first();
    
        if (!$userPassReset) {
            // Create a new reset token
            Forgot_pass::create([
                'email' => $user->email,
                'token' => $resetPasswordToken,
            ]);
        } else {
            // Update the existing token
            $userPassReset->update([
                'token' => $resetPasswordToken,
            ]);
        }
    
        Mail::to($user->email)->send(new ResetPasswordOTP($resetPasswordToken));

       
        // Return the response with a success message and token (for testing purposes)
        return response()->json([
            'message' => 'Otp send to your email',
            'OTP' => $resetPasswordToken
         
        ], 200);
    }
    
    public function reset(Request $request)
    {
        // Validate the incoming request 
        $validatedData = $request->validate([
            'email' => 'required|email|string',
            'token' => 'required',
            'password' => 'required'
        ]);
    
        // Find the user by email
        $user = User::where('email', $validatedData['email'])->first();
    
        if (!$user) {
            return response()->json(['message' => 'No Record Found, Try another email']);
        }
    
        // Find the reset request by email
        $resetRequest = Forgot_pass::where('email', $user->email)->first();
    
        // Check if the reset request exists and if the token matches
        if (!$resetRequest || $resetRequest->token != $request->token) {
            return response()->json(['message' => 'Wrong OTP, Please try again']);
        }
    
        // Update the user's password
        $user->fill([
            'password' => Hash::make($validatedData['password']),
        ]);
        $user->save();
    
        // Delete used token from the database (Forgot_pass table)
        $resetRequest->delete();
    
        // Delete all existing tokens for the user (logout from other sessions)
        $user->tokens()->delete();
    

    
        return response()->json([
            'message' => 'Password reset successful',
        ]);
    }


   
}

