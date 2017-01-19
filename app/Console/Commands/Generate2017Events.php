<?php

namespace App\Console\Commands;

use App\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class Generate2017Events extends Command
{
    const YAML_DATE = 'Y-m-d H:i:s O';

    protected $signature = 'cocoa:events-2017';

    protected $description = 'Generates and overwrites YAML file for 2017 events.';

    public function handle()
    {
        // https://github.com/briannesbitt/Carbon/issues/627
        Carbon::useMonthsOverflow(false);

        //2017 events
        $year = 2017;

        $date = Carbon::createFromDate($year, 1, 1, new \DateTimeZone('Australia/Melbourne'));

        $events = [];

        do {
            // main events are 2nd Thursday of the month, excluding January
            if ($date->month > 1) {
                $date->nthOfMonth(2, Carbon::THURSDAY)->setTime(18, 30);

                //save the date
                $events[] = $this->meetup($date->copy());
            }

            // hack nights last Tuesday of the month (including January)
            $date->lastOfMonth(Carbon::TUESDAY)->setTime(18, 30);
            $events[] = $this->hacknight($date->copy());

            $date->addMonth();
        } while ((integer) $date->year === (integer) $year);

        $yaml = Yaml::dump(['events' => $events], 3);
        $yamlPath = base_path('events.yml');

        file_put_contents($yamlPath, $yaml);

        $this->info("Events written to $yamlPath");
    }

    private function hacknight(Carbon $date) : array
    {
        $date = $date->copy()->setTimezone(new \DateTimeZone('UTC'));

        $event = [
            'type' => Event::HACKNIGHT,
            'title' => 'Hack Night',
            'subtitle' => 'Melbourne Cocoaheads Hack Night',
            'slug' => Str::slug(Event::HACKNIGHT . " $date"),
            'starts_at' => $date->format(self::YAML_DATE),
            'ends_at' => $date->copy()->addHours(3)->format(self::YAML_DATE),
            'contact' => 'https://twitter.com/tupps',
            'contact_name' => 'Luke Tupper',
            'location' => 'The Mill House',
            'location_link' => 'http://themillhouse.com.au',
            'address_display' => '277 Flinders Lane (between Elizabeth & Swanston)',
            'address' => '277 Flinders Lane, Melbourne, VIC 3000',
            'lat' => -37.8171867,
            'lng' => 144.9652635,
        ];

        return $event;
    }
    
    private function meetup(Carbon $date) : array
    {
        $tickets2017 = [
            'https://melbournecocoaheads-feb-2017.eventbrite.com.au',
            'https://melbournecocoaheads-mar-2017.eventbrite.com.au',
            'https://melbournecocoaheads-apr-2017.eventbrite.com.au',
            'https://melbournecocoaheads-may-2017.eventbrite.com.au',
            'https://melbournecocoaheads-jun-2017.eventbrite.com.au',
            'https://melbournecocoaheads-jul-2017.eventbrite.com.au',
            'https://melbournecocoaheads-aug-2017.eventbrite.com.au',
            'https://melbournecocoaheads-sep-2017.eventbrite.com.au',
            'https://melbournecocoaheads-oct-2017.eventbrite.com.au',
            'https://melbournecocoaheads-nov-2017.eventbrite.com.au',
            'https://melbournecocoaheads-dec-2017.eventbrite.com.au',
        ];

        /**
         * @var $date Carbon
         */
        $date = $date->copy()->setTimezone(new \DateTimeZone('UTC'));

        $eventNumber = Event::eventNumberDate($date);

        $event = [
            'type' => Event::MEETUP,
            'slug' => Str::slug(Event::MEETUP . " $date"),
            'starts_at' => $date->format(self::YAML_DATE),
            'ends_at' => $date->copy()->addHours(3)->format(self::YAML_DATE),
            'tickets' => $tickets2017[$date->month - 2],
            'contact' => 'mailto:jesse@jcmultimedia.com.au',
            'contact_name' => 'Jesse Collis',
            'title' => "Melbourne Cocoaheads #$eventNumber",
            'subtitle' => 'Melbourne Cocoaheads Meetup',
            'location' => 'Outware',
            'location_link' => 'http://www.outware.com.au/contact',
            'address_display' => 'Level 3, 469 La Trobe Street, Melbourne',
            'address' => 'Level 3, 469 La Trobe Street, Melbourne VIC 3000',
            'lat' => -37.8126541,
            'lng' => 144.9551303,
            'sponsors' => ['Outware']
        ];

        // Special cases month by month

        // TODO: fill in sponsors for rest of 2017
        switch ($date->month) {
            case 2:
                array_unshift($event['sponsors'], 'Playgrounds');
                break;
            case 7:
                $event['title'] = "Melbourne Cocoaheads #$eventNumber - Lightning Talk Month";
                $event['subtitle'] = 'This month we do away with longer form talks and fill the night with lightning talks!';
                break;
        }

        return $event;
    }
}