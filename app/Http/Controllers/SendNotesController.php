<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\notes;
use App\Models\SendNotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SendNotesController extends Controller
{
    public function viewSentNotes()
    {
        // Get the authenticated user's email
        $userEmail = Auth::user()->email;
        if ($userEmail){
            // Fetch the notes from sendnotes where the email matches
            $sentNotes = SendNotes::where('send_to', $userEmail)->get();
            // Return the view and pass the sent notes data
            return response()->json($sentNotes, 200);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }

    }

    public function sendNotes(Request $request)
    {
        $field = $request->validate([
            'notes_id' => 'required|exists:notes,id',
            'send_to' => "required|email",
            'sent_by' => 'required|string'
        ]);
        
        $sentTo = User::where('email', $field['send_to'])->first();

        if (!$sentTo) {
            return response()->json(['message' => 'Email not found'], 404);
        }
        $note = notes::find($field['notes_id']);

        $send = SendNotes::create([
            'notes_id' => $field['notes_id'],
            'title' => $note->title,
            'contents' => $note->contents,
            'send_to' => $field['send_to'],
            'sent_by' => $field['sent_by'],
            'user_id' => $note->user_id
        ]);

        return response()->json($send, 201);
    }

    public function deleteSentNotes($sendNotes_id)
    {
        try {
            // Find the SendNotes model by its ID
            $sendNotes = SendNotes::findOrFail($sendNotes_id); // Throws 404 if not found

            // Attempt to delete the note
            if ($sendNotes->delete()) {
                return response()->json(['message' => 'Note deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'Failed to delete note'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['Error message' => $e->getMessage()], 500);
        }
    }
}
