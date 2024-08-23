<?php
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\NotesController;
use Illuminate\Support\Facades\Route;



//to add notes
Route::apiResource('notes', NotesController::class);


//Authentication routes "To access"
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
