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

        $flashcardExists = Flashcards::where('title', $fields ['title'])->first();

        if($flashcardExists){
            return response()->json([
                'error' => 'title already exist. Try another title'
            ],409);
        }

        $flashcards = $request->User()->linkToFlashcards()->create($fields);

        return response()->json($flashcards, 201);
    }

    public function show(Request $request){
        $user = $request->User();
        $flashcards = Flashcards::where('user_id', $user->id)->get();

        return request()->json([
            'status' => 'Success',
            'cards' => $flashcards
        ]);
    }

    public function update(Request $request, Flashcards $flashcard) {
        Gate::authorize('modify', $flashcard);
        $fields = $request->validate([
            'title' => 'required|string|max:100',
            'cards' => 'required|array',
        ]);


        $flashcard->update($fields);

        return response()->json([
            'status' => 'Success',
            'cards' => $flashcard
        ]);
    }

    public function destroy(Flashcards $flashcards){
        Gate::authorize('modify', $flashcards);
        $flashcards->delete();
        return ['message'=>'deleted notes succesfully'];
    }
}
