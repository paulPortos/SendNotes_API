<?php

namespace App\Http\Controllers;

use App\Models\notes;
use Illuminate\Http\Request;
use App\Models\Notifications;


class NotificationsController extends Controller
{
    public function shownotif(Request $request)
    {
        $user = $request->user(); // Ensure the user is authenticated
        if ($user) {
            $notif = Notifications::where('user_id', $user->id)->get();
            return response()->json($notif); // Return as JSON for consistency in APIs
        } else {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    }

    public function noteDeclinedNotif(Request $request)
    {
        try {
            // Validate the request
            $fields = $request->validate([
                'notes_id' => 'required|exists:notes,id',
                'email' => 'required|email|string',
                'message' => 'required|string'
            ]);

            $note = notes::find($fields['notes_id']);

            // Try to create the notification
            $notif = Notifications::create([
                'notes_id' => $fields['notes_id'],
                'notes_title' => $note->title,
                'notification_type' => 'Admin has declined your note to be shared',
                'email' => $fields['email'],
                'message' => $fields['message'],
                'user_id' => $note->user_id,
            ]);

            // Return a success response
            return response()->json([
                'message' => 'Notification created successfully',
                'notification' => $notif
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation error messages
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        }
    }

    public function noteAcceptedNotif(Request $request)
    {
        try {
            // Validate the request
            $fields = $request->validate([
                'email' => 'required|email|string',
                'message' => 'required|string'
            ]);

            // Try to create the notification
            $notif = Notifications::create([
                'notification_type' => 'Admin has approved your note to be shared',
                'email' => $fields['email'],
                'message' => $fields['message'],
            ]);

            // Return a success response
            return response()->json([
                'message' => 'Notification created successfully',
                'notification' => $notif
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation error messages
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function notePendingNotif(Request $request)
    {
        try {
            // Validate the request
            $fields = $request->validate([
                'email' => 'required|email|string',
                'message' => 'required|string'
            ]);

            // Try to create the notification
            $notif = Notifications::create([
                'notification_type' => 'Your note is pending for approval',
                'email' => $fields['email'],
                'message' => $fields['message'],
            ]);

            // Return a success response
            return response()->json([
                'message' => 'Notification created successfully',
                'notification' => $notif
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation error messages
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
