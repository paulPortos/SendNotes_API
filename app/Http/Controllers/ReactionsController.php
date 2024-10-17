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

    $react = $request->User()->linkToReactions()->create([
        'likes' => 1,
        'dislikes' => 0,  // Remove any dislike by default
        'notes_id' => $note_id,
        'user_id' => $request->user()->id,
    ]);

    return response()->json(['message' => 'Like added successfully', 'likes' => 1, $react]);
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
            if ($reaction->dislikes > 0) {
                // User already disliked the note, so we don't allow another dislike
                return response()->json(['message' => 'You have already disliked this note'], 403);
            } else {
                // If the user liked the note before, remove the like and add a dislike
                $reaction->likes = 0;      // Remove the like
                $reaction->dislikes = 1;   // Add the dislike
                $reaction->save();

                return response()->json(['message' => 'Like removed and dislike added successfully', 'dislikes' => $reaction->dislikes]);
            }
        }

        // If no reaction exists, create a new one with a dislike
        $react = $request->User()->linkToReactions()->create([
            'likes' => 1,
            'dislikes' => 0,  // Remove any dislike by default
            'notes_id' => $note_id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Dislike added successfully', 'dislikes' => 1, $react]);
    }

    public function resetReactions($note_id)
{
    // Find the reactions by the note_id passed as a route parameter
    $reaction = Reactions::where('notes_id', $note_id)->first();

    if ($reaction) {
        // Reset the likes and dislikes for the note
        $reaction->likes = 0;
        $reaction->dislikes = 0;
        $reaction->save();

        return response()->json([
            'message' => 'Reactions reset successfully',
            'likes' => $reaction->likes,
            'dislikes' => $reaction->dislikes,
        ]);
    } else {
        // If no reaction is found, return a message
        return response()->json(['message' => 'No reactions found for this note'], 404);
    }
}
}
