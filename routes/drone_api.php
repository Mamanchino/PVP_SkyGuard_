<?php
use App\Http\Controllers\DroneController;
use Illuminate\Support\Facades\Route;
use App\Events\PythonEventTriggered;
use Illuminate\Http\Request;
use App\Models\Drone;
use App\Models\Event;

Route::post('/drones/register', [DroneController::class, 'register']);

Route::post('/drone-events', function(Request $request){
    $validated = $request->validate([
        'drone_name' => 'required|string',
        'event_type' => 'required|string',
        'severity' =>   'required|string',

    ]);
    $drone = Drone::where('sim_vehicle_name', $validated['drone_name']) -> firstOrFail();
    Event::create([
        'drone_id' => $drone->id,
        'event_type' => "{$validated['event_type']}",
        'severity' => "{$validated['severity']}",
        'started_at' => now(),
    ]);

    event(new PythonEventTriggered($validated['drone_name'], $validated['event_type'] ));

    return response() -> json(['status' => 'success', 'message' => 'Notification dispatched']);
});
?>