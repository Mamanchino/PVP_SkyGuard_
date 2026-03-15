<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class ForgotPassController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('forgot-password');
    }
}