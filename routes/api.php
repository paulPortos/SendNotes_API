<?php

use App\Models\SendNotes;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ReactionsController;
use App\Http\Controllers\SendNotesController;
use App\Http\Controllers\FlashcardsController;
use App\Http\Controllers\SendNotificationEmail;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\AuthenticationController;

//to Admin
Route::apiResource('/admin', AdminController::class);
//update the public
Route::put('/adminUpdater/{noteId}', [AdminController::class, 'updateNoteAsAdmin']);
//to add notes
Route::apiResource('/notes', NotesController::class);

//comments
Route::apiResource('/comments', CommentsController::class);
Route::get('/comments/note/{note_id}', [CommentsController::class, 'getCommentsByNoteId']);
Route::delete('/notes/{note_id}/comments/{comment_id}', [CommentsController::class, 'deleteUserCommentByNoteId'])->middleware('auth:sanctum');


//Show public notes (false)
Route::get('pending_notes', [AdminController::class, 'showPublicFalse']);
//show public notes
Route::get('/public_notes',[NotesController::class,'public']);

//to add flashcards
Route::apiResource('/flashcards', FlashcardsController::class);

//Authentication routes "To access"
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [AuthenticationController::class, 'register']);
Route::delete('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
//verify emailaddress
Route::get('/email/verify/{id}/{hash}', [AuthenticationController::class, 'verify'])->name('verification.verify');
Route::get('/email/resend', [AuthenticationController::class, 'resend'])->name('verification.resend');

//change password
Route::put('/ChangePass', [ChangePassword::class, 'ChangePass'])->middleware('auth:sanctum');

//forgot password
Route::post('/forgot',[ForgotController::class,'forgot']);
Route::post('/reset',[ForgotController::class,'reset']);

//Notification Routes
Route::middleware('auth:sanctum')->get('/shownotif', [NotificationsController::class, 'shownotif']);
Route::post('/noteAccepted', [NotificationsController::class, 'noteAcceptedNotif']);
Route::post('/noteDecline', [NotificationsController::class, 'noteDeclinedNotif']);
Route::post('/notePending', [NotificationsController::class, 'notePendingNotif']);

Route::post('/sendApproveEmail', [SendNotificationEmail::class, 'sendApprovedNotificationEmail']);
Route::post('/sendDeclineEmail', [SendNotificationEmail::class, 'sendDeclineNotificationEmail']);

Route::get('/showReactions/{note_id}', [ReactionsController::class, 'showReactions']);
Route::middleware('auth:sanctum')->get('getSpecificNote/{note_id}', [ReactionsController::class, 'getSpecificNote']);
Route::middleware('auth:sanctum')->post('/likePost/{note_id}', [ReactionsController::class, 'likePost']);
Route::middleware('auth:sanctum')->post('/dislikePost/{note_id}', [ReactionsController::class, 'dislikePost']);

Route::middleware('auth:sanctum')->get('/viewSentNotes', [SendNotesController::class, 'viewSentNotes']);
Route::middleware('auth:sanctum')->post('/sendNotes', [SendNotesController::class, 'sendNotes']);
Route::middleware('auth:sanctum')->delete('/deleteSentNote/{sendNotes_id}', [SendNotesController::class, 'deleteSentNotes']);
