<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\notes;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
       return Admin::all();
    }
    public function store(Request $request)
{
    try {
        // Validate the request data
        $fields = $request->validate([
            'title' => 'required|string|max:255',
            'creator_username' => 'required|string',
            'creator_email' => 'required|email',
            'contents' => 'required|string',
            'public' => 'required|boolean',
            'note_id' => ['required', 'exists:notes,id'], // Make sure note_id exists in notes table
        ]);

        // Check for an existing note with the same title and creator
        $existingNote = Admin::where('title', $fields['title'])
            ->where('creator_username', $fields['creator_username'])
            ->first();

        if ($existingNote) {
            // Return a custom error response if the note already exists
            return response()->json([
                'error' => 'A note with this title and creator already exists.'
            ], 400);
        }

        // Create a new admin record
        $admin = Admin::create([
            'title' => $fields['title'],
            'creator_username' => $fields['creator_username'],
            'creator_email' => $fields['creator_email'],
            'contents' => $fields['contents'],
            'public' => $fields['public'],
            'note_id' => $fields['note_id'], // Ensure note_id is passed here
        ]);

        // Return the newly created admin as a JSON response
        return response()->json($admin, 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Handle validation errors
        return response()->json([
            'error' => $e->errors(),
        ], 422);

    } catch (\Illuminate\Database\QueryException $e) {
        // Handle database errors, such as foreign key constraint violations
        return response()->json([
            'error' => 'Database error: ' . $e->getMessage(),
        ], 500);

    } catch (\Exception $e) {
        // Handle any other type of exceptions
        return response()->json([
            'error' => 'An unexpected error occurred: ' . $e->getMessage(),
        ], 500);
    }
}


    public function show($id)
    {
        // Find the note by its id
        $note = Admin::where('id', $id)->firstOrFail();

        return response()->json($note);
    }
    public function update(Request $request, Admin $admin)
    {
        $fields = $request->validate([
            'title' => 'required|string|max:255',
            'creator_username' => 'required|string',
            'creator_email' => 'required|email',
            'contents' => 'required|string',
            'public' => 'required|boolean'
        ]);

        $updated = $admin -> update($fields);
        return response()->json($admin, 201);
    }
    public function destroy(Admin $admin)
    {
        $del = $admin -> delete();
        return response()->json([
            'message' => 'successfully deleted'
        ],201);
    }

 // Method to update notes without requiring login
 // Method to update notes without requiring login
 public function updateNoteAsAdmin(Request $request, $noteId)
 {
     // Find the note by its ID
     $note = notes::findOrFail($noteId);

     // No need for authorization checks since this is a public access
     // Validate the request
     $fields = $request->validate([
         'title' => 'required|string|max:255',
         'contents' => 'required|string',
         'public' => 'boolean',
         'to_public' => 'boolean'
     ]);

     // Update the note
     $note->update($fields);
     
     

     return response()->json(['message' => 'Note updated successfully', 'note' => $note]);
 }
}
