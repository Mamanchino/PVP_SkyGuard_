<?php

use App\Http\Controllers\Controller;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [Controller::class, 'showLoginForm'])->name('login');
Route::get('signup', [Controller::class, 'showSignUpForm'])->name('signup');
