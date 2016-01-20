<?php

namespace App\Http\Controllers;

use App\Event;
use Carbon\Carbon;

class EventsController extends Controller
{
    public function getEvents()
    {
        $events = Event::with('posts')
            ->where('ends_at', '>=', Carbon::now())
            ->orderBy('starts_at')
            ->paginate(10);

        return view('events', ['events' => $events, 'title' => 'Upcoming Events']);
    }

    public function getSingleEvent(Event $event)
    {
        return view('events', ['events' => [$event], 'title' => $event->title]);
    }

    public function getPastEvents()
    {
        $events = Event::where('ends_at', '<', Carbon::now())
            ->orderBy('ends_at', 'desc')
            ->paginate(10);

        return view('events', ['events' => $events, 'title' => 'Past Events']);
    }
}
