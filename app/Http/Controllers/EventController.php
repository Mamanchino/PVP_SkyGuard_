<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function markRead(Event $event){
        $event->update(['read_at'=>now() ]);

        return response()->json(['success'=>true]);
    }
}
