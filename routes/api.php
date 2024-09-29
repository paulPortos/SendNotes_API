<?php
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\FlashcardsController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\NotesController;
use Illuminate\Support\Facades\Route;



//to add notes
Route::apiResource('notes', NotesController::class);
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



