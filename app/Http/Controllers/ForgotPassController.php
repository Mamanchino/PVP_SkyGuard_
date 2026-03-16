<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class ForgotPassController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        // For safety reason return success message even if user doesn't exist to prevent email enumeration
        if (!$user) {
            return back()->with('success', 'Message sent, please check your inbox.');
        }

        // Generate token
        $token = Str::random(64);

        $user->update([
            'reset_token_hash'       => hash('sha256', $token),
            'reset_token_expires_at' => Carbon::now()->addHour(),
        ]);

        // Send email
        $resetLink = url('/reset-password?token=' . $token . '&email=' . $user->email);

        Mail::send('emails.reset-password', ['resetLink' => $resetLink], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('SkyGuard - Reset Your Password');
        });

        return back()->with('success', 'Message sent, please check your inbox.');
    }
}