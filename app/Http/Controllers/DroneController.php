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

}
?>