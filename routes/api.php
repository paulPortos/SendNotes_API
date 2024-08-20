<?php
use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;
//Authentication routes "To access"
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
