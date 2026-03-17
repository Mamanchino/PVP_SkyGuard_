<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\ForgotPassController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('login', [Controller::class, 'showLoginForm'])->name('login');
Route::get('signup', [Controller::class, 'showSignUpForm'])->name('signup');

# Login form
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

# Sign-up form
Route::get('/signup', [SignUpController::class, 'showForm'])->name('signup');
Route::post('/signup', [SignUpController::class, 'signUp']);

# Forgot password form
Route::get('/forgot-password', [ForgotPassController::class, 'showForgotPasswordForm'])->name('forgot-password.form');
Route::post('/forgot-password', [ForgotPassController::class, 'sendResetLink'])->name('forgot-password');

# Reset password form
Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('reset-password.form');
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('reset-password');

# Profile page
Route::get('/profile', function () {
    return view('profile', ['user' => auth()->user()]);
})->middleware('auth')->name('profile');