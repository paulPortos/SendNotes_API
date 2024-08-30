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

    public function index(Request $request){
        $user = $request->User();
        $flashcards = Flashcards::where('user_id', $user->id)->get();

       return $flashcards;

    }

    public function store(Request $request){
        $fields = $request -> validate([
            'title' => 'required|string|max:100',
            'cards' => 'required|array',
            'cards.*' => 'required|string',
            'public' => 'required|boolean'
        ]);

        $flashcards = $request->User()->linkToFlashcards()->create($fields);

        return response()->json($flashcards, 201);
    }

    public function show(Request $request){
        $user = $request->User();
        $flashcards = Flashcards::where('user_id', $user->id)->get();

        return $request()->json([
            'status' => 'Success',
            'cards' => $flashcards
        ]);
    }

    public function update(Request $request, Flashcards $flashcards){
         Gate::authorize('modify',$flashcards);
         $fields = $request -> validate([
            'title' => 'required|string|max:100',
            'cards' => 'required|array',
         ]);

         if (isset($fields['title'])) {
            $flashcards->title = $fields['title'];
        }

        // Update the flashcard's title if provided
        if (isset($fields['cards'])) {
            // Get current cards from the JSON column
            $currentCards = $flashcards->cards;

            foreach ($fields['cards'] as $index => $newCard) {
                if (isset($currentCards[$index])) {
                    // Update existing card
                    $currentCards[$index] = $newCard;
                } else {
                    // Add new card if the index doesn't exist
                    $currentCards[] = $newCard;
                }
            }

            $flashcards->cards = $currentCards; // Assign updated cards back to the model
        }

         $flashcards -> update($fields);
         return $request()->json([
            'status' => 'Success',
            'cards' => $flashcards
        ]);
    }

    public function destroy(Flashcards $flashcards){
        Gate::authorize('modify', $flashcards);
        $flashcards->delete();
        return ['message'=>'deleted notes succesfully'];
    }
}
