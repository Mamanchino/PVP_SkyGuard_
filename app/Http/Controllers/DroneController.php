<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Drone;
use App\Models\Event;

class DroneController
{

    private $model_img = array(
        'DJI Mini 5 Pro' => "images/DJI_drone_1.png",
        'DJI Mavic 4 Pro' => "images/DJI_drone_2.png",
        'DJI Neo 2' => "images/DJI_drone_3.png"
    );
    public function register(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string|unique:drones,serial_number',
            'activation_code' => 'required|string',
            'stream_url' => 'nullable|url',
        ]);

        $drone = Drone::where('serial_number', $request->serial_number)->first();

        if (!$drone) {

            $drone = Drone::create(
                [
                    'serial_number' => $request->serial_number,
                    'activation_code' => $request->activation_code,
                    'stream_url' => $request->stream_url,
                    'status' => 'offline',
                    'battery_level' => 100,
                    'is_registered' => false,
                    'model' => $request->model,
                ]
            );
        }
        return response()->json([
            'message' => 'Drone registered successfully',
            'drone_id' => $drone->id
        ]);
    }
    public function add(Request $request)
    {
        $request->validate([
            'activation_code' => 'required|string',
        ]);

        $drone = Drone::where('activation_code', $request->activation_code)
            ->first();

        if (!$drone) {
            return back()->withErrors(['serial' => 'Invalid drone credentials']);
        }

        if ($drone->is_registered) {
            return back()->withErrors(['serial' => 'Drone already assigned']);
        }

        $drone->user_id = auth()->id();
        $drone->is_registered = true;
        $drone->stream_url = env('DETECTION_STREAM_URL');
        $drone->save();

        return redirect()->route('add-drone')
            ->with('success', 'Drone added successfully');
    }
    public function remove(Drone $drone)
    {
        if ($drone->user_id !== auth()->id()) {
            abort(403);
        }

        $drone->user_id = null;
        $drone->is_registered = false;
        $drone->stream_url = null;
        $drone->save();

        return redirect()->route('add-drone')->with('success', 'Drone removed.');
    }
    public function get_devices()
    {
        $userID = auth()->id();
        $assignedDevices = Drone::where('user_id', $userID)->where('is_registered', true)->get() ?? collect();
        return view('add-drone', [
            'assignedDevices' => $assignedDevices,
            'model_img' => $this->model_img
        ]);

    }
    public function showDashboard(Drone $drone)
    {
        if ($drone->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this drone');
        }

        $bridgeBaseURL = rtrim((string) env('AIRSIM_BRIDGE_URL', 'http://localhost:5000'), '/');
        $frameURL = null;


        if (!empty($drone->sim_vehicle_name)) {
            $frameURL = $bridgeBaseURL . '/frame?vehicle_name=' . rawurlencode($drone->sim_vehicle_name);
        }
        $events = Event::where('drone_id', $drone->id)->orderByDesc('started_at')->get();

        return view('dashboard', [
            'drone' => $drone,
            'model_img' => $this->model_img,
            'frameUrl' => $frameURL,
            'events' => $events,
        ]);
    }
    public function stream(Drone $drone)
    {
        if ($drone->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this drone stream.');
        }

        if (!$drone->stream_url) {
            abort(404, 'No stream configured for this drone.');
        }

        return view('streaming', [
            'drone' => $drone,
            'streamUrl' => $drone->stream_url,
        ]);
    }
    public function updateStreamUrl(Request $request, Drone $drone)
    {
        if ($drone->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['stream_url' => 'required|url']);
        $drone->update(['stream_url' => $request->stream_url]);

        return back()->with('success', 'Stream URL updated.');
    }

    public function sendNotification(Drone $drone, Request $request)
    {
        abort_unless($drone->user_id === auth()->id(), 403);

        
        return response()->stream(function() use ($drone, $request){

            $lastEventId = (int) $request->query('last_event_id', 0);
            while (!connection_aborted()) {
                $notification = Event::where('drone_id', $drone->id)
                    ->where('id', '>', $lastEventId)->orderBy('id', 'asc')->get();

                    if ($notification->isNotEmpty()){
                        echo "data: " . json_encode($notification) . "\n\n";
                        $lastEventId = $notification->last()->id;
                    } 
                    if (ob_get_level() > 0)
                        ob_flush();
                    flush();
                
                usleep(500000); // check every 0.5s
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);





        //     abort_unless($drone->user_id === auth()->id(), 403);
        // return response()->stream(function () use ($drone, $request) {
        //     $lastEventId = (int) $request->query('last_event_id', 0);

        //     while (!connection_aborted()) {
        //         $events = Event::where('drone_id',  $drone->id)
        //             ->where('id', '>', $lastEventId)    
        //             ->orderBy('id')
        //             ->get();
        //         foreach ($events as $event) {
        //             $lastEventId = $event->id;
        //             echo "event: person-detected\n";
        //             echo 'data: ' . json_encode([
        //                 'drone_id' => $event->drone_id,
        //                 'event_type' => $event->event_type,
        //                 'severity' => $event->severity,
        //                 'started_at' => $event->started_at,
        //                 'read_at' => $event->read_at,
        //                 'resolved_at' => $event->resolved_at

        //             ]) . "\n\n";

        //             @ob_flush();
        //             flush();
        //         }
        //         sleep(1);
        //     }

        // }, 200, [
        //     'Content-Type' => 'text/event-stream',
        //     'Cache-Control' => 'no-cache',
        //     'Connection' => 'keep-alive',
        //     'X-Accel-Buffering' => 'no',
        // ]);
    }
}
?>