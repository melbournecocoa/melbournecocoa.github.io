<?php

namespace App\Console\Commands;

use App\Event;
use App\Sponsor;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateYearlyEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cocoa:events {year? : The year to create events for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // internally, if there's no year we will do the current year
        $year = $this->argument('year') ?? date('Y');

        $this->createSponsors();

        /**
         * @var $date Carbon
         */
        $date = Carbon::createFromDate($year, 1, 1, new \DateTimeZone('Australia/Melbourne'));

        do {
            // events are 2nd Thursday of the month, not January
            if ($date->month > 1) {
                $date->nthOfMonth(2, Carbon::THURSDAY)->setTime(18, 30);
                $this->createMeetupEvent($date);
            }

            // hack nights last Tuesday of the month
            $date->lastOfMonth(Carbon::TUESDAY)->setTime(18, 30);

            $this->createHackNightEvent($date);
            $date = $date->copy()->startOfMonth()->addMonth();

        } while ((integer) $date->year === (integer) $year);
    }

    private function createHackNightEvent(Carbon $startDate)
    {
        $startDate = $startDate->copy()->setTimezone(new \DateTimeZone('UTC'));

        $this->info("Hack Night : $startDate UTC");

        // Jan event is on the first wednesday
        if ($startDate->month === 1) {
            $startDate->addDay(1);
        }

        // using slug as a unique string identifier
        $slug = Str::slug(Event::HACKNIGHT . " $startDate");

        $event = Event::firstOrNew(['slug' => $slug]);

        $event->type = Event::HACKNIGHT;
        $event->slug = $slug;
        $event->title = 'Hack Night';
        $event->subtitle = 'Melbourne Cocoaheads Hack Night';
        $event->starts_at = $startDate;
        $event->ends_at = $startDate->copy()->addHours(3);
        $event->contact = 'https://twitter.com/tupps';
        $event->contact_name = 'Luke Tupper';

        if ($startDate->month === 1) {
            $event->location = 'Little Creatures Dining Hall';
            $event->location_link = 'https://littlecreatures.com.au/venues/2-melbourne-dining-hall';
            $event->address_display = '222 Brunswick Street, Fitzroy';
            $event->address = '222 Brunswick Street, Fitzroy, VIC 3065';
            $event->lat = -37.8008;
            $event->lng = 144.978;
        } else {
            $event->location = 'Location TBD';
            $event->location_link = 'http://www.melbournecocoaheads.com';
            $event->address_display = 'Location TBD';
            $event->address = 'Location TBD';
            $event->lat = -37.8153744;
            $event->lng = 144.958427;
        }

        $event->save();
    }

    private function createMeetupEvent(Carbon $startDate)
    {
        $startDate = $startDate->copy()->setTimezone(new \DateTimeZone('UTC'));

        // rough algorithm for the number of cch we're at.
        // - december 2015 meetup was #90. We have 11 meetups a year
        // TODO: this can be placed on the model
        $count = 90 + (($startDate->year - 2016) * 11) + ($startDate->month - 1);

        $this->info("Meetup : $count @ $startDate UTC");

        $tickets = [
            'https://melbournecocoaheads-feb-2016.eventbrite.com.au',
            'https://melbournecocoaheads-mar-2016.eventbrite.com.au',
            'https://melbournecocoaheads-apr-2016.eventbrite.com.au',
            'https://melbournecocoaheads-may-2016.eventbrite.com.au',
            'https://melbournecocoaheads-jun-2016.eventbrite.com.au',
            'https://melbournecocoaheads-jul-2016.eventbrite.com.au',
            'https://melbournecocoaheads-aug-2016.eventbrite.com.au',
            'https://melbournecocoaheads-sep-2016.eventbrite.com.au',
            'https://melbournecocoaheads-oct-2016.eventbrite.com.au',
            'https://melbournecocoaheads-nov-2016.eventbrite.com.au',
            'https://melbournecocoaheads-dec-2016.eventbrite.com.au'
        ];

        // using slug as a unique string identifier
        $slug = Str::slug(Event::MEETUP . " $startDate");

        $event = Event::firstOrNew(['slug' => $slug]);
        $event->type = Event::MEETUP;
        $event->slug = Str::slug("$event->type $startDate");
        $event->starts_at = $startDate;
        $event->ends_at = $startDate->copy()->addHours(3);
        $event->location = 'Teamsquare';
        $event->location_link = 'https://teamsquare.co/contact';
        $event->address_display = 'Level 1, 520 Bourke Street, Melbourne';
        $event->address = 'Level 1, 520 Bourke Street, Melbourne VIC 3000';
        $event->lat = -37.8153744;
        $event->lng = 144.958427;
        $event->tickets = $tickets[$startDate->month - 2];
        $event->contact = 'mailto:jesse@jcmultimedia.com.au';
        $event->contact_name = 'Jesse Collis';

        if ($startDate->month === 5) {
            $event->title = "Melbourne Cocoaheads #$count - Lightning Talk Month";
            $event->subtitle = 'This month we do away with longer form talks and fill the night with lightning talks!';
        } elseif ($startDate->month === 6) {
            $event->title = "Melbourne Cocoaheads #$count - (WWDC Tentative Date)";
            $event->subtitle = 'The date for this meetup is tentative due to WWDC being held in June. We will reschedule this meetup to be AFTER the WWDC conference.';
        } else {
            $event->title = "Melbourne Cocoaheads #$count";
            $event->subtitle = 'Melbourne Cocoaheads Meetup';
        }

        $event->save();

        // save the event, add sponsors
        $event->sponsors()->save(Sponsor::first());
    }

    //TODO: create sponsors where required.
    private function createSponsors()
    {
        factory(\App\Sponsor::class)->create();
    }
}
