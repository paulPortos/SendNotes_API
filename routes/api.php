<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\FlashcardsController;
use App\Http\Controllers\PublicNotesController;
use App\Http\Controllers\AuthenticationController;


//to Admin
Route::apiResource('admin', AdminController::class);

//To Public notes (Home)
Route::apiResource('public_notes', PublicNotesController::class);

//to add notes
Route::apiResource('notes', NotesController::class);
//to add flashcards
Route::apiResource('flashcards', FlashcardsController::class);

//Authentication routes "To access"
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
