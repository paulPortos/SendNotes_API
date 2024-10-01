<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
       return Admin::all();
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

        $existingNote = admin::where('title', $fields['title'])
        ->where('creator_username', $fields['creator_username'])
        ->first();
        if ($existingNote)
        {
        // Return a custom error response
        return response()->json([
        'error' => 'A note with this title and creator already exists.'
        ], 400);
        }

        $admin = Admin::create($fields);
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
}
