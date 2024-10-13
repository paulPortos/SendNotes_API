<?php

namespace App\Http\Controllers;

use App\Models\notes;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CommentsController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return[
            new Middleware('auth:sanctum')
        ];
    }
  public function index(){
        return Comments::all();
  }

  public function store(Request $request){
    $fields  = $request->validate([
        'username'=>'required|string',
        'notes_id' => 'required|exists:notes,id',
        'comment'=> 'required',

    ]);

    $comments = $request ->User()->linktocomments()->create([
        'username' => $request->username,
        'notes_id' => $request->notes_id,
        'comment' => $request->comment,
        'user_id' => $request->user()->id,  // Associate the logged-in user
    ]);

    return $comments;
     
    
  }
  
  public function show($commentId)
  {
      // Find the comment by ID
      $comment = Comments::find($commentId);
  
      // Check if the comment exists
      if (!$comment) {
          return response()->json(['error' => 'Comment not found'], 404); // Not found response
      }
  
      // Return the found comment
      return response()->json($comment, 200);
  }
  
    public function update(Request $request, Comments $comments)
    {
        $fields  = $request->validate([
            'username'=>'required|string',
            'notes_id' => 'required|exists:notes,id',
            'comment'=> 'required',
    
        ]);
    }
    public function destroy(Comments $comment)
{
    Gate::authorize('modifycomment', $comment);
    $comment->delete(); // This will now correctly delete the comment instance
    return response()->json(['message' => 'Comment deleted successfully'], 200);
}
    
public function getCommentsByNoteId($note_id)
    {
        // Check if the note exists
        $note = notes::find($note_id);

        if (!$note) {
            return response()->json(['error' => 'Note not found'], 404);
        }

        // Retrieve all comments for the specific note
        $comments = Comments::where('notes_id', $note_id)->get();

        // Return the comments
        return response()->json($comments, 200);
    }

    public function deleteUserCommentByNoteId(Request $request, $note_id, $comment_id)
{
    // Check if the note exists
    $note = notes::find($note_id);

    if (!$note) {
        return response()->json(['error' => 'Note not found'], 404);
    }

    // Find the comment by ID
    $comment = Comments::find($comment_id);

    // Check if the comment exists
    if (!$comment) {
        return response()->json(['error' => 'Comment not found'], 404);
    }

    // Check if the comment belongs to the authenticated user
    if ($comment->user_id !== $request->user()->id) {
        return response()->json(['error' => 'You are not authorized to delete this comment'], 403); // Forbidden response
    }

    // If authorized, delete the comment
    $comment->delete();

    return response()->json(['message' => 'Comment deleted successfully'], 200);
}
}
