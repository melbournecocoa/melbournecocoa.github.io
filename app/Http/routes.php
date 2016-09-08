<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', ['as' => 'home', 'uses' => 'PostsController@getHome']);

    Route::get('/updates', ['as' => 'posts', 'uses' => 'PostsController@getPosts']);
    Route::get('/updates/{post}', ['as' => 'post', 'uses' => 'PostsController@getSinglePost']);

    Route::get('/events', ['as' => 'events', 'uses' => 'EventsController@getEvents']);
    Route::get('/events/{event}', ['as' => 'event', 'uses' => 'EventsController@getSingleEvent']);
    Route::get('/event/{event}', ['as' => 'eventSingle', 'uses' => 'EventsController@getSingleEvent']);

    Route::get('/about', ['as' => 'about', function () {
        return view('about');
    }]);

    Route::get('/contact', ['as' => 'contact', function () {
        return view('contact');
    }]);
//    Route::get('/sitemap', ['as' => 'sitemap', 'uses' => 'PostsController@xxx']);

    Route::get('/live', function () {
        return response('', 303)->header('location', 'http://www.youtube.com/channel/UCpTDVzUkk9ieAyVyUi28bWw/live');
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
});

Route::group(['prefix' => '/api', 'middleware' => ['api']], function () {

    Route::get('/events', function () {
        //TODO: Cache
        $events = (new \App\Event())->nextEvent()->limit(null)->get();
        $hacknights = (new \App\Event())->where('type', '=', \App\Event::HACKNIGHT)->get();
        return response()->json(['meetups' => $events, 'hacknights' => $hacknights]);
    });

    Route::get('/events/next', function () {
        //TODO: Cache
        $event = (new \App\Event)->nextEvent()->first();
        $hacknight = (new \App\Event)->nextHacknight()->first();

        return response()->json(['meetups' => [$event], 'hacknights' => [$hacknight]]);
    });

    Route::get('/events/past', function () {
        //TODO: Cache
        $events = (new \App\Event())->where('type', '=', \App\Event::MEETUP)->get();
        $hacknights = (new \App\Event())->where('type', '=', \App\Event::HACKNIGHT)->get();
        return response()->json(['meetups' => $events, 'hacknights' => $hacknights]);
    });

});