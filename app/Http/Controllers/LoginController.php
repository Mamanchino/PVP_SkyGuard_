<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email_or_username' => 'required',
            'password' => 'required',
        ]);

        // Check if user exists
        $user = User::where('email', $request->email_or_username)
                    ->orWhere('name', $request->email_or_username)
                    ->first();

        if ($user && Auth::attempt(['email' => $user->email, 'password' => $request->password]) || 
            ($user && Auth::attempt(['name' => $user->name, 'password' => $request->password]))) {
            return redirect()->intended('/'); // No dashboard yet so just redirect to home
        }

        return back()->withErrors([
            'email_or_username' => 'The provided credentials are incorrect.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
