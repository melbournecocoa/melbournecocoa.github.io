<?php

namespace App\Console\Commands;

use App\Event;
use App\Sponsor;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Yaml\Parser;

class LoadEvents extends Command
{
    protected $signature = 'cocoa:events';

    protected $description = 'Load posts from events.yml.';

    public function handle()
    {
        $yaml = new Parser();
        $events = $yaml->parse(file_get_contents(base_path('events.yml')));

        foreach ($events['events'] as $event) {
            $slug = $event['slug'];

            $e = Event::firstOrNew(['slug' => $slug]);
            $e->type = $event['type'];

            $startsAt = Carbon::createFromFormat(Generate2017Events::YAML_DATE, $event['starts_at']);
            $e->starts_at = $startsAt;

            $endsAt = Carbon::createFromFormat(Generate2017Events::YAML_DATE, $event['ends_at']);
            $e->ends_at = $endsAt;

            $e->contact = $event['contact'];
            $e->contact_name = $event['contact_name'];
            $e->location = $event['location'];
            $e->location_link = $event['location_link'];
            $e->address_display = $event['address_display'];
            $e->address = $event['address'];
            $e->title = $event['title'];
            $e->subtitle = $event['subtitle'];
            $e->lat = $event['lat'];
            $e->lng = $event['lng'];

            $e->tickets = isset($event['tickets']) ? $event['tickets'] : '';

            $e->save();

            $sponsors = $e['sponsors'];
            if (is_array($sponsors)) {
                foreach ($sponsors as $sponsor) {
                    $e->sponsors()->attach(Sponsor::where('name', '=', $sponsor)->first());
                }
            }
        }
    }
}
