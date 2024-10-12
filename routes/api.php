<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\FlashcardsController;
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


//show public notes (true)
Route::get('public_notes',[NotesController::class,'public']);

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



