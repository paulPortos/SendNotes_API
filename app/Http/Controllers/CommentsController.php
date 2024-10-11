<?php

namespace App\Http\Controllers;

use App\Models\notes;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Egulias\EmailValidator\Parser\Comment;
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
  public function index(Request $request){
        return Comments::all();
  }

  public function store(Request $request){
    $fields  = $request->validate([
        'username'=>'required|string',
        'notes_id' => 'required|exists:notes,id',
        'comments'=> 'required',

    ]);

    $notesid = notes::find($fields['notes_id']);


    if(!$notesid){
        return response()->json(['error' => 'Note not found'], 404);
    }

    $comments = comments::create([
        'username' => $fields['username'],
        'comments' => $fields['comments'],
        'notes_id' => $fields['notes_id'],
        'user_id' => $notesid->user_id  // Get user_id from the retrieved note
    ]);
     
      // Return a success response with the newly created admin record
      return response()->json($comments, 201);
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
            'comments'=> 'required',
    
        ]);
    }
    public function destroy(Comments $comment)
{
    Gate::authorize('modify', $comment);
    $comment->delete(); // This will now correctly delete the comment instance
    return response()->json(['message' => 'Comment deleted successfully'], 200);
}
    
    public function getCommentsByNoteId($note_id)
{
    // Check if the note exists
    
    $note = notes::find($note_id);

    if(!$note){
        return response()->json(['error' => 'Note not found'], 404);
    }

    // Retrieve all comments for the specific note
    $comments = Comments::where('notes_id', $note_id)->get();

    // Return the comments
    return response()->json($comments, 200);
}
}
