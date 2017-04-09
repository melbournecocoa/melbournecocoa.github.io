<?php

use App\Event;

Route::get('/events', function () {
    /**
     * @var $events \Illuminate\Database\Eloquent\Collection
     */
    $events = Event::where('ends_at', '>=', \Carbon\Carbon::now())
        ->orderBy('starts_at', 'asc')
        ->get();

    $eventsArray = $events->map(function (Event $event) {
        $a = $event->toArray();
        ksort($a);
        return $a;
    });

    return response()
        ->json(['events' => $eventsArray])
        ->setEncodingOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});
