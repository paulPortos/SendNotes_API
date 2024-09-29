<?php

use App\Http\Controllers\ForgotController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/otptester', function () {
    return view('OtpNotif'); // Ensure you have a corresponding Blade view
});
