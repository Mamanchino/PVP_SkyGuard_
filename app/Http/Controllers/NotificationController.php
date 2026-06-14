<?php
namespace App\Http\Controllers;

use App\Models\DroneNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Called by detected_stream.py when a person is detected.
     * Protected by a shared secret header, NOT session auth.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'drone_id'   => 'required|integer',
            'type'       => 'sometimes|string|max:64',
            'message'    => 'required|string|max:255',
            'confidence' => 'sometimes|numeric|between:0,1',
        ]);

        // Look up the owner from the drones table
        $drone = \App\Models\Drone::findOrFail($validated['drone_id']);
        $validated['user_id'] = $drone->user_id;

        $notif = DroneNotification::create($validated);

        return response()->json(['id' => $notif->id], 201);
    }

    /**
     * Polled by the frontend every few seconds.
     */
    public function index(Request $request)
    {
        $user     = Auth::user();
        $droneId  = $request->query('drone_id');
        $since    = $request->query('since'); // ISO timestamp or null

        $query = DroneNotification::where('user_id', $user->id)
            ->where('drone_id', $droneId)
            ->orderBy('created_at', 'desc')
            ->limit(50);

        if ($since) {
            $query->where('created_at', '>', $since);
        }

        return response()->json($query->get());
    }

    /**
     * Mark one or all notifications as read.
     */
    public function markRead(Request $request)
    {
        $user    = Auth::user();
        $droneId = $request->input('drone_id');

        DroneNotification::where('user_id', $user->id)
            ->where('drone_id', $droneId)
            ->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }
}