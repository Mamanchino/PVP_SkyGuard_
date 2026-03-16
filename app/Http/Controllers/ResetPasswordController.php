<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request)
    {
        return view('reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/\d/',
                'regex:/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/']
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Invalid reset link.');
        }

        $tokenValid = hash_equals(
            $user->reset_token_hash,
            hash('sha256', $request->token)
        );

        if (!$tokenValid) {
            return back()->with('error', 'Invalid reset link.');
        }

        if (Carbon::now()->isAfter($user->reset_token_expires_at)) {
            return back()->with('error', 'This reset link has expired. Please request a new one.');
        }

        // Same password
        if (Hash::check($request->password, $user->password)) {
            return back()->with('error', 'same_password');
        }

        $user->update([
            'password'               => Hash::make($request->password),
            'reset_token_hash'       => null,
            'reset_token_expires_at' => null,
        ]);

        return redirect('/login')->with('success', 'Password reset successfully. Please log in.');
    }
}