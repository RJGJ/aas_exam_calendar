<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, Response};
use App\Models\Event;

class EventController extends Controller
{
    public function saveEvent(Request $request): Response
    {
        $event = Event::create([
            'title' => $request->title,
            'from' => date('Y-m-d', $request->from),
            'to' => date('Y-m-d', $request->to),
            'days' => $request->days
        ]);
        return response([
            'event' => $event
        ]);
    }

    public function getAllEvents(Request $request): Response
    {
        $events = Event::get();
        return response([
            'events' => $events
        ]);
    }
}
