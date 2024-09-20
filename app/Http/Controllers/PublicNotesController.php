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
            'contents' => 'required|string',
            'public' => 'required|boolean'
        ]);

        $admin = PublicNotes::create($fields);
        return response()->json($admin, 201);
    }
    public function show(PublicNotes $PublicNotes)
    {
       return $PublicNotes;
    }
    public function update(Request $request, PublicNotes $PublicNotes)
    {
        $fields = $request->validate([
            'title' => 'required|string|max:255',
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
