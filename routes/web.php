<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\ForgotPassController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChangePasswordController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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

# Change password form
Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->middleware('auth')->name('change-password.form');
Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('change-password');

# Profile page
Route::get('/profile', function () {
    return view('profile', ['user' => auth()->user()]);
})->middleware('auth')->name('profile');

#Home page
Route::get('/add-drone', function (){
    return view('add-drone');
})->middleware('auth')->name('add-drone');

# Dashboard page
Route::get('/dashboard', function (){
    return view('dashboard');
})->middleware('auth')->name('dashboard');