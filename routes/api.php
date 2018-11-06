<?php

use App\Event;
use Symfony\Component\Yaml\Parser;

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

Route::get('/', function () {
    return view('apidocs');
});

Route::get('/openapi.json', function () {
    //load swagger.yaml, turn into JSON, return it

    $yaml = new Parser();

    $posts = $yaml->parse(file_get_contents(base_path('openapi.yml')));

    return response()
        ->json($posts)
        ->setEncodingOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
})->name('api-spec');
