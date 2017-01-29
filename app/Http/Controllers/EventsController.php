<?php

namespace App\Http\Controllers;

use App\Event;
use Carbon\Carbon;
use SEO;

class EventsController extends Controller
{
    public function getUpcomingEvents()
    {
        $events = Event::with('posts')
            ->upcomingEvents()
            ->paginate(10);

        SEO::setTitle('Upcoming Events - Melbourne CocoaHeads');
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

    public function getEvent($eventId)
    {
        $event = Event::findOrFail((integer) $eventId);

        $events = Event::with('posts')
            ->where('id', '=', $event->id)
            ->paginate(10);

        SEO::setTitle("$event->title - Melbourne CocoaHeads");
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

        SEO::setTitle('Upcoming Events - Melbourne CocoaHeads');
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
        $events = (new Event)->upcomingEvents()->get();

        $header = <<<EOT
BEGIN:VCALENDAR
PRODID:-//Melbourne CocoaHeads//Event Calendar//EN
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:Melbourne CocoaHeads
X-WR-TIMEZONE:UTC
X-WR-CALDESC:Calendar for Melbourne CocoaHeads. Meetings are scheduled for the second Thursday of the month from
 February to December.For more inform ation visit http://www.melbournecocoaheads.com and
 https://groups.google.com/group/cocoaheadsau and @melbournecocoa on twitter.
EOT;

        $body = '';
        foreach ($events as $event) {
            $startTimestamp = $event->starts_at->format('Ymd\THis\Z');
            $endsTimestamp = $event->ends_at->format('Ymd\THis\Z');
            $createdTimestamp = $event->created_at->format('Ymd\THis\Z');
            $modifiedTimestamp = $event->updated_at->format('Ymd\THis\Z');
            $uid = $event->id;

            $address = str_replace(",", "\\,", $event->address_display);

            $eventBody = <<<EOT
BEGIN:VEVENT
DTSTART:$startTimestamp
DTEND:$endsTimestamp
UID:$uid
CLASS:PUBLIC
CREATED:$createdTimestamp
DESCRIPTION:$event->subtitle see {$event->url()} for more information.
LAST-MODIFIED:$modifiedTimestamp
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY:$event->title
GEO:$event->lat;$event->lng
LOCATION:$address
TRANSP:TRANSPARENT
END:VEVENT

EOT;
            $body .= $eventBody;
        }

        $footer = <<<EOT
END:VCALENDAR
EOT;

        /**
         * TODO: Cache-Control: no-cache, no-store, max-age=0, must-revalidate
         */
        $response = str_replace("\r\n", "\n", "$header\n$body\n$footer");
        $response = str_replace("\n", "\r\n", $response);

        return response($response, 200, [
           'Content-Type' => 'text/calendar; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename=cocoaheads-calendar.ics'
        ]);
    }
}
