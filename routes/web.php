<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\ForgotPassController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\DroneController;
use App\Models\Drone;
use App\Models\Event;
use Illuminate\Http\Request;


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

#Add a drone to the user's account
Route::post('/drones/add', [DroneController::class, 'add']);
Route::get('/add-drone', [DroneController::class, 'get_devices'])->middleware('auth')->name('add-drone');

Route::get('/dashboard/{drone}', [DroneController::class, 'showDashboard'])
    ->middleware('auth')
    ->name('drone.dashboard');

Route::get('/streaming/{drone}', [DroneController::class, 'stream'])
    ->middleware('auth')
    ->name('drone.stream');



Route::get('drone-events', function (Drone $drone, Request $request) {
    abort_unless($drone->user_id === auth()->id(), 403);
    return response()->stream(function () use ($drone, $request) {
        $lastEventId = (int) $request->query('last_event_id', 0);

        while (!connection_aborted()) {
            $events = Event::where('drone_id',  $drone->id)
                ->where('id', '>', $lastEventId)    
                ->orderBy('id')
                ->get();
            foreach ($events as $event) {
                $lastEventId = $event->id;
                echo "event: person-detected\n";
                echo 'data: ' . json_encode([
                    'drone_id' => $event->drone_id,
                    'event_type' => $event->event_type,
                    'severity' => $event->severity,
                    'started_at' => $event->started_at,
                    'read_at' => $event->read_at,
                    'resolved_at' => $event->resolved_at
                    
                ]) . "\n\n";

                @ob_flush();
                flush();
            }
            sleep(1);
        }

    }, 200, [
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
        'X-Accel-Buffering' => 'no',
    ]);
})->middleware('auth');


Route::patch('/drones/{drone}/stream-url', [DroneController::class, 'updateStreamUrl'])->middleware('auth')->name('drone.updateStreamUrl');
Route::delete('/drones/{drone}/remove', [DroneController::class, 'remove'])->middleware('auth')->name('drone.remove');