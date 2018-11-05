<?php

use App\Event;

Route::get('/events', function () {
    /**
     * @var $events \Illuminate\Database\Eloquent\Collection
     */
    $events = Event::where('ends_at', '>=', \Carbon\Carbon::now())
        ->orderBy('starts_at', 'asc')
        ->get();

    return (new \App\Http\Resources\EventCollection($events))
        ->response()
        ->setEncodingOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

})
    ->name('events-api');

Route::get('/events/{event}', function (Event $event) {
    return (new \App\Http\Resources\Event($event))
        ->response()
        ->setEncodingOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
})
    ->name('events-api-event');
