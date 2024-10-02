<?php

namespace App\Http\Controllers;

use App\Models\Flashcards;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Http\Request;

class FlashcardsController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return[
            new Middleware('auth:sanctum')
        ];
    }

    public function index(Request $request)
    {
        $user = $request->User();
        $flashcards = Flashcards::where('user_id', $user->id)->get();

       return $flashcards;
    }

    public function store(Request $request)
    {
        $fields = $request -> validate([
            'title' => 'required|string|max:100',
            'cards' => 'required|array',
            'cards.*' => 'required|string',
            'public' => 'boolean',
            'to_public' => 'boolean',
        ]);
        //Checks if the flashcard exist in the database
        $flashcardExists = Flashcards::where('title', $fields ['title'])->first();

        if($flashcardExists)
        {
            return response()->json([
                'error' => 'title already exist. Try another title'
            ],409);
        }

        $flashcards = $request->User()->linkToFlashcards()->create($fields);

        return response()->json($flashcards, 201);
    }

    public function show(Request $request, $id)
{
    // Get the authenticated user
    $user = $request->user();

    // Retrieve the specific flashcard by its ID and ensure it belongs to the authenticated user
    $flashcard = Flashcards::where('user_id', $user->id)
                           ->where('id', $id)
                           ->first();

    // Check if the flashcard was found
    if (!$flashcard) {
        return response()->json([
            'status' => 'Failed',
            'message' => 'Flashcard not found for this user.'
        ], 404);
    }

    // Return the specific flashcard
    return response()->json([
        'status' => 'Success',
        'card' => $flashcard
    ], 200);
}


    public function update(Request $request, Flashcards $flashcard)
    {
        Gate::authorize('modify', $flashcard);
        $fields = $request->validate([
            'title' => 'required|string|max:100',
            'cards' => 'required|array',
            'public' => 'boolean',
            'to_public' => 'boolean',
        ]);
        //Checks if the flashcard exist in the database
        $flashcardExists = Flashcards::where('title', $fields ['title'])->first();

        $flashcard->update($fields);
            return response()->json([
                'status' => 'Success',
                'cards' => $flashcard
            ]);


        // if($flashcardExists)
        // {
        //     return response()->json([
        //         'error' => 'title already exist. Try another title'
        //     ],409);
        // }
        // else if(!$flashcardExists)
        // {
        //     $flashcard->update($fields);
        //     return response()->json([
        //         'status' => 'Success',
        //         'cards' => $flashcard
        //     ]);
        // }
    }

    public function destroy(Flashcards $flashcard)
    {
        Gate::authorize('modify', $flashcard);
        $flashcard->delete();
        return response()->json(
            ['message'=>'deleted flashcards succesfully'],200
        );
    }
}
