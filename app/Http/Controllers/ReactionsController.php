<?php

namespace App\Http\Controllers;

use App\Models\notes;
use App\Models\Reactions;
use Illuminate\Http\Request;

class ReactionsController extends Controller
{

    public function showReactions($note_id)
    {
        // Find the reaction by the note_id passed as a route parameter
        $reaction = Reactions::where('notes_id', $note_id)->first();

        if ($reaction) {
            // Return the likes and dislikes counts for the note
            return response()->json([
                'likes' => $reaction->likes,
                'dislikes' => $reaction->dislikes,
            ]);
        } else {
            // If no reaction is found, return a message
            return response()->json(['message' => 'No reactions found for this note'], 404);
        }
    }

    public function likePost($note_id)
{
    $data = request()->validate([
        'user_id' => 'required|integer',
    ]);

    // Check if a reaction already exists for this user and note
    $reaction = Reactions::where('notes_id', $note_id)
                         ->where('user_id', $data['user_id'])
                         ->first();

    if ($reaction) {
        if ($reaction->likes > 0) {
            // User already liked the note, so we don't allow another like
            return response()->json(['message' => 'You have already liked this note'], 403);
        } else {
            // If the user disliked the note before, remove the dislike and add a like
            $reaction->likes = 1;      // Add the like
            $reaction->dislikes = 0;   // Remove the dislike
            $reaction->save();

            return response()->json(['message' => 'Dislike removed and like added successfully', 'likes' => $reaction->likes]);
        }
    }

    // If no reaction exists, create a new one with a like
    Reactions::create([
        'likes' => 1,
        'dislikes' => 0,  // Remove any dislike by default
        'notes_id' => $note_id,
        'user_id' => $data['user_id']
    ]);

    return response()->json(['message' => 'Like added successfully', 'likes' => 1]);
    }
}
