<?php
use App\Http\Controllers\DroneController;
use Illuminate\Support\Facades\Route;


Route::post('/drones/register', [DroneController::class, 'register']);

?>