<?php

namespace App\Console\Commands;

use App\Event;
use App\Sponsor;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class Generate2016Events extends Command
{
    protected $signature = 'cocoa:events-2016';

    protected $description = '2016 event creator. Also creates sponsors.';
    
    public function handle()
    {
        // // https://github.com/briannesbitt/Carbon/issues/627
        Carbon::useMonthsOverflow(false);

        $this->createSponsors();

        $year = 2016;

        $date = Carbon::createFromDate($year, 1, 1, new \DateTimeZone('Australia/Melbourne'));

        do {
            // events are 2nd Thursday of the month, not January
            if ($date->month > 1) {
                $date->nthOfMonth(2, Carbon::THURSDAY)->setTime(18, 30);
                $this->createMeetupEvent2016($date->copy());
            }

            // hack nights last Tuesday of the month
            $date->lastOfMonth(Carbon::TUESDAY)->setTime(18, 30);
            $this->createHackNightEvent2016($date->copy());

            if ($date->month > 7) {
                $date->firstOfMonth(Carbon::FRIDAY)->setTime(7, 30);

                if ($date->month == 11) {
                    $date->addDays(7); // delayed one week in November
                }

                $this->createNSBreakfastEvent2016($date->copy());
            }

            $date->addMonth();

        } while ((integer) $date->year === (integer) $year);

        $this->createSpecialEvents2016();
    }

    private function createHackNightEvent2016(Carbon $startDate)
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
        $event->subtitle = 'Melbourne CocoaHeads Hack Night';
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
        } elseif ($startDate->month >= 2 && $startDate->month < 5) {
            $event->location = '1000 £ Bend';
            $event->location_link = 'http://thousandpoundbend.com.au';
            $event->address_display = '361 Little Lonsdale St, Melbourne';
            $event->address = '361 Little Lonsdale St, Melbourne, VIC 3000';
            $event->lat = -37.811672;
            $event->lng = 144.959092;
        } elseif ($startDate->month >= 5) {
            $event->location = 'The Mill House';
            $event->location_link = 'http://themillhouse.com.au';
            $event->address_display = '277 Flinders Lane (between Elizabeth & Swanston)';
            $event->address = '277 Flinders Lane, Melbourne, VIC 3000';
            $event->lat = -37.8171867;
            $event->lng = 144.9652635;
        }
        /* else {
            $event->location = 'Location TBD';
            $event->location_link = 'http://www.melbournecocoaheads.com';
            $event->address_display = 'Location TBD';
            $event->address = 'Location TBD';
            $event->lat = -37.8153744;
            $event->lng = 144.958427;
        }*/

        $event->save();
    }

    private function createMeetupEvent2016(Carbon $startDate)
    {
        $startDate = $startDate->copy()->setTimezone(new \DateTimeZone('UTC'));

        // rough algorithm for the number of cch we're at.
        // - December 2015 meetup was #90. We have 11 meetups a year
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
        $event->tickets = $tickets[$startDate->month - 2];
        $event->contact = 'mailto:jesse@jcmultimedia.com.au';
        $event->contact_name = 'Jesse Collis';

        // venue changes half way through 2016
        if ($event->starts_at->month > 1 && $event->starts_at->month < 8) {
            $event->location = 'Teamsquare';
            $event->location_link = 'https://teamsquare.co/contact';
            $event->address_display = 'Level 1, 520 Bourke Street, Melbourne';
            $event->address = 'Level 1, 520 Bourke Street, Melbourne VIC 3000';
            $event->lat = -37.8153744;
            $event->lng = 144.958427;
        } elseif ($event->starts_at->month > 1 && $event->starts_at->month <= 12) {
            $event->location = 'Outware';
            $event->location_link = 'http://www.outware.com.au/contact/';
            $event->address_display = 'Level 3, 469 La Trobe Street, Melbourne';
            $event->address = 'Level 3, 469 La Trobe Street, Melbourne VIC 3000';
            $event->lat = -37.8126541;
            $event->lng = 144.9551303;
        }

        if ($startDate->month === 7) {
            $event->title = "Melbourne CocoaHeads #$count - Lightning Talk Month";
            $event->subtitle = 'This month we do away with longer form talks and fill the night with lightning talks!';
        } else {
            $event->title = "Melbourne CocoaHeads #$count";
            $event->subtitle = 'Melbourne CocoaHeads Meetup';
        }

        $event->save();

        $event->sponsors()->detach();

        switch ($event->starts_at->month) {
            case 1:
                break;
            case 2:
                $event->sponsors()->attach(Sponsor::where('name', 'B2Cloud')->first());
                $event->sponsors()->attach(Sponsor::where('name', '=', 'Teamsquare')->first());
                break;
            case 3:
                $event->sponsors()->attach(Sponsor::where('name', 'jtribe')->first());
                $event->sponsors()->attach(Sponsor::where('name', '=', 'Teamsquare')->first());
                break;
            case 4:
                $event->sponsors()->attach(Sponsor::where('name', 'Vinomofo')->first());
                $event->sponsors()->attach(Sponsor::where('name', '=', 'Teamsquare')->first());
                break;
            case 5:
                $event->sponsors()->attach(Sponsor::where('name', 'Domestic Cat')->first());
                $event->sponsors()->attach(Sponsor::where('name', '=', 'Teamsquare')->first());
                break;
            case 6:
                $event->sponsors()->attach(Sponsor::where('name', 'Odecee')->first());
                $event->sponsors()->attach(Sponsor::where('name', '=', 'Teamsquare')->first());
                break;
            case 7:
                $event->sponsors()->attach(Sponsor::where('name', 'Domestic Cat')->first());
                $event->sponsors()->attach(Sponsor::where('name', '=', 'Teamsquare')->first());
                break;
            case 8:
                $event->sponsors()->attach(Sponsor::where('name', '=', 'Outware')->first());
                break;
            case 9:
                $event->sponsors()->attach(Sponsor::where('name', 'Domestic Cat')->first());
                $event->sponsors()->attach(Sponsor::where('name', '=', 'Outware')->first());
                break;
            case 10:
                $event->sponsors()->attach(Sponsor::where('name', 'iflix')->first());
                $event->sponsors()->attach(Sponsor::where('name', '=', 'Outware')->first());
                break;
            case 11:
                $event->sponsors()->attach(Sponsor::where('name', 'Domestic Cat')->first());
                $event->sponsors()->attach(Sponsor::where('name', '=', 'Outware')->first());
                break;
            case 12:
                $event->sponsors()->attach(Sponsor::where('name', 'Bilue')->first());
                $event->sponsors()->attach(Sponsor::where('name', '=', 'Outware')->first());
                break;
        }
    }

    private function createSponsors()
    {
        $sponsors = [
            [
                'name' => 'Teamsquare',
                'image' => 'sponsors/teamsquare.png',
                'web' => 'https://teamsquare.co',
                'twitter' => 'teamsquare',
                'contact' => 'Michael Shimmins'
            ],
            [
                'name' => 'B2Cloud',
                'image' => 'sponsors/b2cloud.jpg',
                'web' => 'http://b2cloud.com.au',
                'twitter' => 'b2cloud',
                'contact' => 'Josh Guest'
            ],
            [
                'name' => 'jtribe',
                'image' => 'sponsors/jtribe.png',
                'web' => 'http://jtribe.com.au',
                'twitter' => 'jtribeapps',
                'contact' => 'Armin Kroll'
            ],
            [
                'name' => 'Vinomofo',
                'image' => 'sponsors/vinomofo.png',
                'web' => 'https://vinomofo.com',
                'twitter' => 'vinomofo',
                'contact' => 'Luke Cunningham'
            ],
            [
                'name' => 'Domestic Cat',
                'image' => 'sponsors/domestic_cat.png',
                'web' => 'http://domesticcat.com.au',
                'twitter' => '',
                'contact' => 'Pat Richards'
            ],
            [
                'name' => 'Odecee',
                'image' => 'sponsors/odecee.jpg',
                'web' => 'http://odecee.com.au',
                'twitter' => 'odecee',
                'contact' => 'Ashton Williams'
            ],
            [
                'name' => 'Outware',
                'image' => 'sponsors/Outware.jpg',
                'web' => 'http://www.outware.com.au',
                'twitter' => 'outware',
                'contact' => 'Gideon Kowadlo'
            ],
            [
                'name' => 'Realestate.com.au',
                'image' => 'sponsors/realestate.jpg',
                'web' => 'http://techblog.realestate.com.au',
                'twitter' => 'realestate_au',
                'contact' => null
            ],
            [
                'name' => 'Bilue',
                'image' => 'sponsors/bilue.png',
                'web' => 'https://bilue.com.au',
                'twitter' => 'bilue_voice',
                'contact' => 'Stewart Gleadow'
            ],
            [
                'name' => 'iflix',
                'image' => 'sponsors/iflix.png',
                'web' => 'https://jobs.lever.co/iflix.com',
                'twitter' => 'iflixMY',
                'contact' => 'Ray Hilton'
            ],
            [
                'name' => 'Playgrounds',
                'image' => 'sponsors/playgrounds.png',
                'web' => 'http://www.playgroundscon.com',
                'twitter' => 'playgroundscon',
                'contact' => 'Andyy Hope'
            ],
            [
                'name' => 'Dius',
                'image' => 'sponsors/dius.png',
                'web' => 'http://dius.com.au',
                'twitter' => 'dius_au',
                'contact' => 'Ian Donaldson',
            ],
        ];

        foreach ($sponsors as $s) {
            $sponsor = Sponsor::firstOrNew(['name' => $s['name']]);
            $sponsor->image = $s['image'];
            $sponsor->web = $s['web'];
            $sponsor->twitter = $s['twitter'];
            $sponsor->contact = $s['contact'];
            $sponsor->save();
        }
    }

    private function createNSBreakfastEvent2016(Carbon $startDate)
    {
        $startDate = $startDate->copy()->setTimezone(new \DateTimeZone('UTC'));

        $slug = Str::slug(Event::SPECIAL . " $startDate");

        $this->info("NSBreakfast $startDate UTC");

        $event = Event::firstOrNew(['slug' => $slug]);

        $event->type = Event::SPECIAL;
        $event->slug = $slug;
        $event->title = 'NSBreakfast';
        $event->subtitle = 'Informal and unstructured; Hang out, drink coffee, eat breakfast and chat iOS / OSX';
        $event->starts_at = $startDate;
        $event->ends_at = $startDate->copy()->addHours(2);
        $event->contact = 'https://twitter.com/nsbreakfast';
        $event->contact_name = 'Matt Delves';
        $event->location = 'Hash Specialty Coffee and Roasters';
        $event->location_link = 'https://www.beanhunter.com/melbourne/hash-melbourne-cbd';
        $event->address_display = '113 Hardware Street, Melbourne';
        $event->address = '113 Hardware Street, Melbourne, VIC 3000';
        $event->lat = -37.81138;
        $event->lng = 144.958709;

        $event->save();
    }

    private function createSpecialEvents2016()
    {
        //NSBreakfast May
        $nsBreakfastMay = Carbon::create(2016, 5, 6, 8, 0, 0, new \DateTimeZone('Australia/Melbourne'));
        $nsBreakfastMay->setTimezone(new \DateTimeZone('UTC'));
        // using slug as a unique string identifier
        $slug = Str::slug(Event::SPECIAL . " $nsBreakfastMay");

        $this->info("Special Event (NSBreakfast) $nsBreakfastMay UTC");

        $event = Event::firstOrNew(['slug' => $slug]);

        $event->type = Event::SPECIAL;
        $event->slug = $slug;
        $event->title = 'NSBreakfast';
        $event->subtitle = 'Informal and unstructured; Hang out, drink coffee, eat breakfast and chat iOS / OSX';
        $event->starts_at = $nsBreakfastMay;
        $event->ends_at = $nsBreakfastMay->copy()->addHours(2);
        $event->contact = 'https://twitter.com/nsbreakfast';
        $event->contact_name = 'Matt Delves';
        $event->location = '1000 £ Bend';
        $event->location_link = 'http://thousandpoundbend.com.au';
        $event->address_display = '361 Little Lonsdale St, Melbourne';
        $event->address = '361 Little Lonsdale St, Melbourne, VIC 3000';
        $event->lat = -37.811672;
        $event->lng = 144.959092;

        $event->save();

        //NSBreakfast June
        $nsBreakfastJune = Carbon::create(2016, 6, 3, 7, 30, 0, new \DateTimeZone('Australia/Melbourne'));
        $nsBreakfastJune->setTimezone(new \DateTimeZone('UTC'));

        $slug = Str::slug(Event::SPECIAL . " $nsBreakfastJune");

        $this->info("Special Event (NSBreakfast) $nsBreakfastJune UTC");

        $event = Event::firstOrNew(['slug' => $slug]);
        $event->type = Event::SPECIAL;
        $event->slug = $slug;
        $event->title = 'NSBreakfast';
        $event->subtitle = 'Informal and unstructured; Hang out, drink coffee, eat breakfast and chat iOS / OSX';
        $event->starts_at = $nsBreakfastJune;
        $event->ends_at = $nsBreakfastJune->copy()->addHours(2);
        $event->contact = 'https://twitter.com/nsbreakfast';
        $event->contact_name = 'Matt Delves';
        $event->location = 'Hash Specialty Coffee';
        $event->location_link = 'http://facebook.com/hashcoffeeroasters';
        $event->address_display = '113 Hardware St, Melbourne';
        $event->address = '113 Hardware St, Melbourne, VIC 3000';
        $event->lat = -37.8123025;
        $event->lng = 144.9605897;
        $event->save();

        //WWDC Event June 2016
        $wwdcEventJune2016 = Carbon::create(2016, 6, 16, 18, 30, 0, new \DateTimeZone('Australia/Melbourne'));
        $wwdcEventJune2016->setTimezone(new \DateTimeZone('UTC'));

        $slug = Str::slug(Event::SPECIAL . " $wwdcEventJune2016");
        $this->info("Special Event (WWDC) $wwdcEventJune2016 UTC");

        $event = Event::firstOrNew(['slug' => $slug]);
        $event->type = Event::SPECIAL;
        $event->slug = $slug;

        $event->title = 'CocoaHeads WWDC Special Event';
        $event->subtitle = <<<'EOT'
We're going to start off with a special intro I'm calling 'The Keynote That Was' (TKTW) and we will then watch two 
to three WWDC sessions. Depending on what's available at the time, we might have a few options but the best ones will be
the State of the Union sessions that are usually presented on the Tuesday morning in San Francisco.
EOT;

        $event->starts_at = $wwdcEventJune2016;
        $event->ends_at = $wwdcEventJune2016->copy()->addHours(3);
        $event->location = 'Teamsquare';
        $event->location_link = 'https://teamsquare.co/contact';
        $event->address_display = 'Level 1, 520 Bourke Street, Melbourne';
        $event->address = 'Level 1, 520 Bourke Street, Melbourne VIC 3000';
        $event->lat = -37.8153744;
        $event->lng = 144.958427;
        $event->tickets = 'http://melbournecocoaheads-wwdc-2016.eventbrite.com.au';

        $event->contact = 'https://twitter.com/melbournecocoa';
        $event->contact_name = 'Jesse Collis';

        $event->save();
        $event->sponsors()->detach();

        $event->sponsors()->attach(Sponsor::where('name', '=', 'Teamsquare')->first());
        $event->sponsors()->attach(Sponsor::where('name', 'Odecee')->first());
    }
}
