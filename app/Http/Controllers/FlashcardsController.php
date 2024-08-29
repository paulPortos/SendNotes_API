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
            'username' => 'required|string',
            'title' => 'required|string|max:100',
            'contents' => 'required|string',
            'public' => 'required|string'

        ]);
        $flashcards = $request ->User()->linkToFlashcards()->create($fields);

        return $flashcards;
    }

    public function show(Request $request){
        $user = $request->User();
        $flashcards = Flashcards::where('user_id', $user->id)->get();

        return $flashcards;
    }

    public function update(Request $request, Flashcards $flashcards){
         Gate::authorize('modify',$flashcards);
         $fields = $request -> validate([
            'title' => 'required|string|max:100',
            'contents' => 'required|string',
         ]);
         $flashcards -> update($fields);
         return $flashcards;
    }

    public function destroy(Flashcards $flashcards){
        Gate::authorize('modify', $flashcards);
        $flashcards->delete();
        return ['message'=>'deleted notes succesfully'];
    }
}
