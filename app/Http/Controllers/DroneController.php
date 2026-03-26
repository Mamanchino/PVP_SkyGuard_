<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Drone;

class DroneController
{
    public function register(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string|unique:drones,serial_number',
            'activation_code' => 'required|string',
        ]);

        $drone = Drone::where('serial_number', $request->serial_number)->first();

        if (!$drone) {

            $drone = Drone::create([
                'serial_number' => $request->serial_number,
                'activation_code' => $request->activation_code,
                'status' => 'offline',
                'battery_level' => 100,
                'is_registered' => false,
            ]);
        }
        return response()->json([
            'message' => 'Drone registered successfully',
            'drone_id' => $drone->id
        ]);
    }
    public function add(Request $request){
        $request->validate([
            'serial'=>'required|string',
            'activation_code' => 'required|string',
        ]);

        $drone = Drone::where('serial_number', $request->serial)
        ->where('activation_code', $request->activation_code)
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
        return view('add-drone', compact('assignedDevices'));
    }
}
?>