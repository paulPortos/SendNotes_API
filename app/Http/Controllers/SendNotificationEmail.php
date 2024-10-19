<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NotificationsEmail;
use Illuminate\Support\Facades\Mail;

class SendNotificationEmail extends Controller
{
    public function sendApprovedNotificationEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'title' => 'required|string',
        ]);

        $message = "has been approved to be shared!";
        $admin_message = "Thank you for sharing your knowledge! Your contribution will greatly assist other users in their reviews and studies.";
        
        $details = [
            'email' => $validated['email'],
            'title' => $validated['title'],
            'message' => $message,
            'admin_message' => $admin_message
        ];

        try {
            Mail::to($details['email'])->send(new NotificationsEmail($details));
            return response()->json(['message' => 'Email sent successfully!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send email. Invalid email address or server error.'], 500);
        }
    }

    public function sendDeclineNotificationEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'title' => 'required|string',
        ]);

        $message = "has been disapproved to be shared!";
        $admin_message = "Your note has been disapproved due to violations of our sharing guidelines. We appreciate your understanding and encourage you to adhere to our community standards.";

        $details = [
            'email' => $validated['email'],
            'title' => $validated['title'],
            'message' => $message,
            'admin_message' => $admin_message
        ];

        try {
            Mail::to($details['email'])->send(new NotificationsEmail($details));
            return response()->json(['message' => 'Email sent successfully!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send email. Invalid email address or server error.'], 500);
        }
    }

}
