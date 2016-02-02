<?php

namespace App\Http\Controllers;

use App\Event;
use Carbon\Carbon;
use SEO;

class EventsController extends Controller
{
    public function getEvents()
    {
        $events = Event::with('posts')
            ->where('ends_at', '>=', Carbon::now())
            ->orderBy('starts_at')
            ->paginate(10);

        SEO::setTitle('Upcoming Events - Melbourne Cocoaheads');
        SEO::setDescription('Meetings are scheduled for the second Thursday of the month from February to December.
    A meeting might be moved forward or back a week depending on where it falls in relation to
                        public holidays or WWDC, so make sure you keep tabs on the schedule by subscribing to Google
                        Calendar, keeping an eye out on the
    <a href="https://groups.google.com/group/cocoaheadsau">Google Group</a> or following
    <a href="https://www.twitter.com/melbournecocoa">@melbournecocoa</a>.');

        SEO::opengraph()->setUrl(request()->fullUrl());
        SEO::opengraph()->addProperty('type', 'articles');

        return view('events', ['events' => $events, 'title' => 'Upcoming Events']);
    }

    public function getSingleEvent(Event $event)
    {
        $events = Event::with('posts')
            ->where('id', '=', $event->id)
            ->paginate(10);

        SEO::setTitle("$event->title - Melbourne Cocoaheads");
        SEO::setDescription($event->subtitle);
        SEO::opengraph()->setUrl($event->url());
        SEO::opengraph()->setArticle([
            'published_time' => $event->created_at,
            'modified_time' => $event->modified_at,
            'expiration_time' => $event->ends_at,
            'author' => 'Jesse Collis',
            'section' => 'updates',
            'tag' => ['updates']
        ]);

        return view('events', ['events' => $events, 'title' => $event->title]);
    }

    public function getPastEvents()
    {
        $events = Event::where('ends_at', '<', Carbon::now())
            ->orderBy('ends_at', 'desc')
            ->paginate(10);

        //FIXME: JC - duplicated

        SEO::setTitle('Upcoming Events - Melbourne Cocoaheads');
        SEO::setDescription('Meetings are scheduled for the second Thursday of the month from February to December.
    A meeting might be moved forward or back a week depending on where it falls in relation to
                        public holidays or WWDC, so make sure you keep tabs on the schedule by subscribing to Google
                        Calendar, keeping an eye out on the
    <a href="https://groups.google.com/group/cocoaheadsau">Google Group</a> or following
    <a href="https://www.twitter.com/melbournecocoa">@melbournecocoa</a>.');

        SEO::opengraph()->setUrl(request()->fullUrl());
        SEO::opengraph()->addProperty('type', 'articles');


        return view('events', ['events' => $events, 'title' => 'Past Events']);
    }

    public function calendar()
    {
        return 'Coming soon!';
    }
}
