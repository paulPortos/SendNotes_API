<?php

namespace App\Http\Controllers;

use App\Models\PublicNotes;
use Illuminate\Http\Request;

class PublicNotesController extends Controller
{
    public function index()
    {
       return PublicNotes::all();
    }
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string|max:255',
            'creator_username' => 'required|string',
            'creator_email' => 'required|email',
            'contents' => 'required|string',
            'public' => 'required|boolean'
        ]);

        $existingNote = PublicNotes::where('title', $fields['title'])
                               ->where('creator_username', $fields['creator_username'])
                               ->first();
        if ($existingNote)
        {
        // Return a custom error response
        return response()->json([
            'error' => 'A note with this title and creator already exists.'
        ], 400);
    }
        $admin = PublicNotes::create($fields);
        return response()->json($admin, 201);
    }

    public function show($id)
    {
        // Find the note by its id
        $note = PublicNotes::where('id', $id)->firstOrFail();

        return response()->json($note);
    }


    public function update(Request $request, PublicNotes $PublicNotes)
    {
        $fields = $request->validate([
            'title' => 'required|string|max:255',
            'creator_username' => 'required|string',
            'creator_email' => 'required|email',
            'contents' => 'required|string',
            'public' => 'required|boolean'
        ]);

        $updated = $PublicNotes -> update($fields);
        return response()->json($PublicNotes, 201);
    }

    public function destroy(PublicNotes $PublicNotes)
    {
        $del = $PublicNotes -> delete();
        return response()->json([
            'message' => 'successfully deleted'
        ],201);
    }
}
