<?php

use App\Event;

Route::get('/events', function () {
    $events = (new Event())->nextEvent()->limit(null)->get();
    $hacknights = (new Event())->where('type', '=', Event::HACKNIGHT)->get();
    return response()->json(['meetups' => $events, 'hacknights' => $hacknights]);
});

Route::get('/events/next', function () {
    $event = (new Event)->nextEvent()->first();
    $hacknight = (new Event)->nextHacknight()->first();

    return response()->json(['meetups' => [$event], 'hacknights' => [$hacknight]]);
});

Route::get('/events/past', function () {
    $events = (new Event())->where('type', '=', Event::MEETUP)->get();
    $hacknights = (new Event())->where('type', '=', Event::HACKNIGHT)->get();
    return response()->json(['meetups' => $events, 'hacknights' => $hacknights]);
});
