<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Drone;

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
        ]);

        $drone = Drone::where('serial_number', $request->serial_number)->first();

        if (!$drone) {

            $drone = Drone::create(
                [
                'serial_number' => $request->serial_number,
                'activation_code' => $request->activation_code,
                'status' => 'offline',
                'battery_level' => 100,
                'is_registered' => false,
                'model'=> $request->model,
            ]);
        }
        return response()->json([
            'message' => 'Drone registered successfully',
            'drone_id' => $drone->id
        ]);
    }
    public function add(Request $request){
        $request->validate([
            'activation_code' => 'required|string',
        ]);

        $drone = Drone::where('activation_code', $request->activation_code)
        ->first();

        if(!$drone){
            return back()->withErrors(['serial' => 'Invalid drone credentials']);
        }

        if($drone->is_registered) {
            return back()->withErrors(['serial' => 'Drone already assigned']);
        }

        $drone->user_id = auth()->id();
        $drone->is_registered = true;
        $drone->save();

        return redirect()->route('add-drone')
                 ->with('success', 'Drone added successfully');
    }
    public function get_devices(){
        $userID = auth()->id();
        $assignedDevices = Drone::where('user_id', $userID)->where('is_registered', true)->get() ?? collect();
        return view('add-drone', [
            'assignedDevices' => $assignedDevices,
            'model_img' => $this->model_img
        ]);

    }
    public function showDashboard(Drone $drone){
        if ($drone->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this drone');
        }

        $bridgeBaseURL = rtrim((string) env('AIRSIM_BRIDGE_URL', 'http://localhost:5000'), '/');
        $frameURL = null;
        
        if (!empty($drone->sim_vehicle_name)) {
            $frameURL = $bridgeBaseURL.'/frame?vehicle_name='. rawurlencode($drone->sim_vehicle_name);
        }
        return view ('dashboard', [
            'drone' => $drone,
            'model_img' => $this->model_img,
            'frameUrl' => $frameURL
        ]);
    }
}
?>
