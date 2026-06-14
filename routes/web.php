<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\ForgotPassController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\DroneController;
use App\Http\Controllers\EventController;
use App\Models\Drone;
use App\Models\Event;
use Illuminate\Http\Request;
use PHPUnit\Event\EventCollection;
use App\Http\Controllers\NotificationController;


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
Route::get('/drones/by-stream-url', [DroneController::class, 'findByStreamUrl']);
Route::post('/drones/add', [DroneController::class, 'add']);
Route::get('/add-drone', [DroneController::class, 'get_devices'])->middleware('auth')->name('add-drone');

Route::get('/dashboard/{drone}', [DroneController::class, 'showDashboard'])
    ->middleware('auth')
    ->name('drone.dashboard');

Route::get('/streaming/{drone}', [DroneController::class, 'stream'])
    ->middleware('auth')
    ->name('drone.stream');



Route::get('/drones/{drone}/drone-events', [DroneController::class, 'sendNotification'])  ->middleware('auth');

// Route::get('/drones/{drone/alerts', function (Drone $drone){
//     abort_unless($drone->user_id === auth()->id(), 403);
//     $alerts = Event::where('drone_id', $drone->id)
//         ->whereIn('severity', ['critical', 'error'])
//         ->whereNull('resolved_at')
//         ->whereNull('read_at')
//         ->exists();
//     return response()->json([
//         'has_alerts' => $alerts,
//     ]);
// })->middleware('auth');

Route::patch('/drones/{drone}/stream-url', [DroneController::class, 'updateStreamUrl'])->middleware('auth')->name('drone.updateStreamUrl');
Route::delete('/drones/{drone}/remove', [DroneController::class, 'remove'])->middleware('auth')->name('drone.remove');

Route::post("/alerts/{event}/read", [EventController::class, 'markRead'])->middleware('auth')->name('alerts.read');

Route::get('/about_us', function (Request $request) {return view('about_us');})->name('about-us');
Route::get('/our_services', function (Request $request) {return view('our-services');})->name('our-services');
Route::get('/faq', function (Request $request) {return view('faq');})->name('faq');
Route::get('/industrial', function (Request $request) {return view('industrial');})->name('industrial');
Route::get('/commercial', function (Request $request) {return view('commercial');})->name('commercial');

# Notification routes
Route::post('/detections', [NotificationController::class, 'store']);
Route::get('/drones/by-stream-url', [DroneController::class, 'findByStreamUrl']); // add here
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/mark-read', [NotificationController::class, 'markRead']);
});

