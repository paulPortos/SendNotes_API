<?php

namespace App\Http\Controllers;

use App\Models\notes;
use App\Models\Reactions;
use Illuminate\Http\Request;

class ReactionsController extends Controller
{

    public function showReactions($note_id)
    {
        // Get all reactions for the specific note
        $reactions = Reactions::where('notes_id', $note_id)->get();

        if ($reactions->isNotEmpty()) {
            // Count the number of likes and dislikes
            $likesCount = $reactions->where('has_liked', true)->count();
            $dislikesCount = $reactions->where('has_disliked', true)->count();

            // Return the counts
            return response()->json([
                'likes' => $likesCount,
                'dislikes' => $dislikesCount,
            ]);
        } else {
            // If no reactions are found, return a message
            return response()->json(['message' => 'No reactions found for this note'], 404);
        }
    }


    public function likePost(Request $request, $note_id)
    {
        $note = notes::find($note_id);

        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }

        $user_id = $request->user()->id;

        // Check if a reaction already exists for this user and note
        $reaction = Reactions::where('notes_id', $note_id)
                             ->where('user_id', $user_id)
                             ->first();

        if ($reaction) {
            // User has not liked or disliked yet
            if ($reaction->has_liked == false && $reaction->has_disliked == false) {
                $reaction->has_liked = true;
                $reaction->save();
            }
            // User has already liked the note, remove like
            elseif ($reaction->has_liked == true && $reaction->has_disliked == false) {
                $reaction->has_liked = false;
                $reaction->save();
            }
            // User previously disliked the note, change to like
            elseif ($reaction->has_liked == false && $reaction->has_disliked == true) {
                $reaction->has_disliked = false;
                $reaction->has_liked = true;
                $reaction->save();
            }

            return response()->json($reaction, 201);
        } else {
            // If no reaction exists, create a new one with a like
            $newReaction = $request->User()->linkToReactions()->create([
                'has_liked' => true,
                'has_disliked' => false,
                'notes_id' => $note_id,
                'user_id' => $user_id,
            ]);

            return response()->json($newReaction, 201);
        }
    }

    public function dislikePost(Request $request, $note_id)
{
    $note = notes::find($note_id);

    if (!$note) {
        return response()->json(['message' => 'Note not found'], 404);
    }

    $user_id = $request->user()->id;

    // Check if a reaction already exists for this user and note
    $reaction = Reactions::where('notes_id', $note_id)
                         ->where('user_id', $user_id)
                         ->first();

    if ($reaction) {
        // User has not liked or disliked yet
        if ($reaction->has_liked == false && $reaction->has_disliked == false) {
            $reaction->has_disliked = true;
            $reaction->save();
        }
        // User has already disliked the note, remove dislike
        elseif ($reaction->has_liked == false && $reaction->has_disliked == true) {
            $reaction->has_disliked = false;
            $reaction->save();
        }
        // User previously liked the note, change to dislike
        elseif ($reaction->has_liked == true && $reaction->has_disliked == false) {
            $reaction->has_liked = false;
            $reaction->has_disliked = true;
            $reaction->save();
        }

        return response()->json($reaction, 201);
    } else {
        // If no reaction exists, create a new one with a dislike
        $newReaction = $request->User()->linkToReactions()->create([
            'has_liked' => false,
            'has_disliked' => true,
            'notes_id' => $note_id,
            'user_id' => $user_id,
        ]);

        return response()->json($newReaction, 201);
        }
    }

    public function getSpecificNote(Request $request, $note_id)
{
    // Find the note by ID
    $note = notes::find($note_id);

    if (!$note) {
        return response()->json(['message' => 'Note not found'], 404);
    }

    // Get the authenticated user ID
    $user_id = $request->user()->id;
    
    // Find the reaction of the user for this specific note
    $reaction = Reactions::where('notes_id', $note_id)
                         ->where('user_id', $user_id)
                         ->first();

    if ($reaction) {
        // If the reaction exists, return the has_liked and has_disliked values
        return response()->json([
            'has_liked' => $reaction->has_liked,
            'has_disliked' => $reaction->has_disliked,
        ], 200);
    } else {
        // If no reaction exists, return false for both
        return response()->json([
            'has_liked' => false,
            'has_disliked' => false,
        ], 200);
    }
}

}
