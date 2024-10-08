<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\notes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
       return Admin::all();
    }
    public function store(Request $request)
{
    // Validate the request input
    $fields = $request->validate([
        'notes_id' => 'required|exists:notes,id',
        'title' => 'required|string|max:255',
        'creator_username' => 'required|string|max:255',
        'creator_email' => 'required|email|max:255',
        'contents' => 'required|string',
        'public' => 'required|boolean',
    ]);

    // Check if an admin note with the same notes_id already exists
    $existingAdmin = Admin::where('notes_id', $fields['notes_id'])->first();

    if ($existingAdmin) {
        return response()->json(['error' => 'An admin note with this notes_id already exists'], 409);
    }

    // Retrieve the note to ensure the notes_id is valid
    $note = notes::find($fields['notes_id']);

    if (!$note) {
        return response()->json(['error' => 'Note not found'], 404);
    }

    // Create the new admin note
    $admin = Admin::create([
        'notes_id' => $fields['notes_id'],
        'title' => $fields['title'],
        'creator_username' => $fields['creator_username'],
        'creator_email' => $fields['creator_email'],
        'contents' => $fields['contents'],
        'public' => $fields['public'],
        'user_id' => $note->user_id,  // Get user_id from the retrieved note
        'to_public' => true
    ]);

    // Return a success response with the newly created admin record
    return response()->json($admin, 201);
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
            'public' => 'required|boolean',
            'to_public' => 'required|boolean'
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


    public function updateNoteAsAdmin(Request $request, $noteId,Admin $admin)
    {
        // Find the note by its ID
        $note = notes::findOrFail($noteId);

        // No need for authorization checks since this is a public access
        // Validate the request
        $fields = $request->validate([
            'public' => 'boolean',
            'to_public' => 'boolean'
        ]);
       // Find the corresponding Admin record by the note_id and delete it
        $admin = Admin::where('notes_id', $note->id)->first(); // Find Admin linked to the note

        if ($admin) {
            $admin->delete(); // Delete the Admin entry linked to the note
        }

        // Update the note
        $note->update($fields);
        return response()->json(['message' => 'Note updated successfully', 'note' => $note]);
    }

    public function showPublicFalse(Request $request)
    {
        $user = $request->User();

       // Fetch all notes that are public (to public is false)
         $admin = admin::where('public', false)->get();

        return $admin;
    }
}
