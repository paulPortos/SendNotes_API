<?php

namespace App\Http\Controllers;

use App\Models\notes;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class NotesController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return[
            new Middleware('auth:sanctum')
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(notes $note)
    {
       
       $note ->notes::all();
       Gate::authorize('modify',$note);
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request -> validate([
            'title' => 'Required|string|max:100',
            'contents' => 'required|string'
        ]);
        $notes = $request ->user()->linkToNotes()->create($fields);

        return $notes;
    }

    /**
     * Display the specified resource.
     */
    public function show(notes $note)
    {
       return $note;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, notes $note)
    {
        Gate::authorize('modify',$note);
        $fields = $request -> validate([
            'title' => 'Required|max:100',
            'contents' => 'required'
        ]);
        $note -> update($fields);
        return $note;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(notes $note)
    {
        Gate::authorize('modify',$note);
        $note->delete();
        return ['message'=>'deleted notes succesfully'];
    }
}
