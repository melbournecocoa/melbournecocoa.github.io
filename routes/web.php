<?php

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::get('/updates', ['as' => 'posts', 'uses' => 'PostsController@getPosts']);
Route::get('/updates/{post}', ['as' => 'post', 'uses' => 'PostsController@getSinglePost']);

Route::get('/events', ['as' => 'events', 'uses' => 'EventsController@getUpcomingEvents']);
Route::get('/events/{event}', ['as' => 'event', 'uses' => 'EventsController@getEvent']);

Route::get('/about', ['as' => 'about', function () {
    return view('about');
}]);

Route::get('/contact', ['as' => 'contact', function () {
    return view('contact');
}]);

Route::get('/live', function () {
    return response('', 303)->header('location', 'http://www.youtube.com/channel/UCpTDVzUkk9ieAyVyUi28bWw/live');
});

Route::get('/2018', function () {
    return response()->redirectToRoute(
        'post',
        ['post' => \App\Post::where('slug', '=', 'cococaheads-2017-summer-break')->firstOrFail()]
    );
});

Route::get('/events/past', ['as' => 'pastEvents', 'uses' => 'EventsController@getPastEvents']);
Route::get('/calendar', ['as' => 'calendar', function () {
    return view('calendar');
}]);
Route::get('/calendar/ical.ics', ['as' => 'calendarFeed', 'uses' => 'EventsController@calendar']);
Route::get('/rss', ['as' => 'feed', 'uses' => 'PostsController@feed']);

Route::get('/slides', ['as'=> 'slides', 'uses' => 'SlidesController@slides']);

Route::get('/talks/list', ['middleware' => 'auth.basic.once', 'uses' => 'TalksController@listTalks']);

Route::post('/talks', ['as' => 'submitTalk', 'uses' => 'TalksController@submitTalk']);

Route::get('/talks', ['as' => 'talks', function () {
    $events = (new \App\Event())->upcomingEvents()->where('type', '=', \App\Event::MEETUP)->get();
    return view('talk', ['events' => $events]);
}]);

Route::get('/talks/success', ['as' => 'submitTalkSuccess', function () {
    return view('talk-success');
}]);
