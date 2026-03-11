<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Controller
{
    public function showLoginForm()
    {
        return view('login');
    }
    public function showSignUpForm()
    {
        return view('signup');
    }
}
