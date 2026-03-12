<?php

use App\Http\Controllers\Controller;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [Controller::class, 'showLoginForm'])->name('login');
Route::get('signup', [Controller::class, 'showSignUpForm'])->name('signup');

Route::get('/forgot-password', function () {
    return view('forgot-password');
})->name('forgot-password');
