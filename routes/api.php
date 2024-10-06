<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FlashcardsController;
use App\Http\Controllers\PublicNotesController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\NotificationsController;

//to Admin
Route::apiResource('admin', AdminController::class);
//update the public
Route::put('/adminUpdater/{noteId}', [AdminController::class, 'updateNoteAsAdmin']);
//to add notes
Route::apiResource('notes', NotesController::class);

//show public notes
Route::get('public_notes',[NotesController::class,'public']);

//to add flashcards
Route::apiResource('flashcards', FlashcardsController::class);

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
Route::get('/shownotif', [NotificationsController::class, 'shownotif']);
Route::post('/makenotif', [NotificationsController::class, 'makenotif']);



