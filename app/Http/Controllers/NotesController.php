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
     * Display a listing of the resource on the loged in user.
     */
    public function index(Request $request)
    {

       $user = $request->User();
       $notes = notes::where('user_id', $user->id)->get();

       return $notes;

    }

    /**
     * Store a newly created resource in storage on the loged in user.
     */
    public function store(Request $request)
    {
        $fields = $request -> validate([
            'title' => 'required|string|max:100',
            'contents' => 'required|string',
            'public' => 'required|boolean',
            'to_public' => 'required|boolean'
        ]);
        $notes = $request ->User()->linkToNotes()->create($fields);

        return $notes;
    }


     //Display the specified resource on the loged in user.

     public function show(Request $request, $id)
     {
        $user = $request->User();
        $notes = notes::where('user_id', $user->id)->where('id', $id)->firstOrFail();

        return $notes;
     }

    /**
     * Update the specified resource in storage on the loged in user.
     */
    public function update(Request $request, notes $note)
    {
        Gate::authorize('modify',$note);
        $fields = $request -> validate([
            'title' => 'Required|max:100',
            'contents' => 'required',
            'public' => 'boolean',
            'to_public' => 'boolean'
        ]);
        $note -> update($fields);
        return $note;
    }

    /**
     * Remove the specified resource from storage on the loged in user..
     */
    public function destroy(notes $note)
    {
        Gate::authorize('modify',$note);
        $note->delete();
        return ['message'=>'deleted notes succesfully'];
    }
    public function public(Request $request)
    {
        $user = $request->User();

       // Fetch all notes that are public (to_public is true)
         $notes = notes::where('public', true)->get(); 

        return $notes;
    }
}
