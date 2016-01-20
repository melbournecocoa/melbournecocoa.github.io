<?php

namespace App\Http\Controllers;

use App\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EventsController extends Controller
{
    public function getEvents()
    {
        $events = Event::where('ends_at', '>=', Carbon::now())->get();

        return view('events', ['events' => $events]);
    }

    public function getEvent(\App\Event $event)
    {
        return view('events', ['events' => [$event]]);
    }
}
