<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignUpController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [Controller::class, 'showLoginForm'])->name('login');
Route::get('signup', [Controller::class, 'showSignUpForm'])->name('signup');

Route::get('/forgot-password', function () {
    return view('forgot-password');
})->name('forgot-password');

# Login form
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

# Sign-up form
Route::get('/signup', [SignUpController::class, 'showForm'])->name('signup');
Route::post('/signup', [SignUpController::class, 'signUp']);
