<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class BootstrapEvents extends Command
{
    protected $signature = 'cocoa:bootstrap-events';

    protected $description = 'Generates a YAML file for 2016/2017 events';

    public function handle()
    {
        //2017 events
        $year = 2017;

        $date = Carbon::createFromDate($year, 1, 1, new \DateTimeZone('Australia/Melbourne'));

        $dateStrings = [];

        do {
            // main events are 2nd Thursday of the month, excluding January
            if ($date->month > 1) {
                $date->nthOfMonth(2, Carbon::THURSDAY)->setTime(18, 30);

                //save the date
                $dateStrings[] = $date->toIso8601String();
            }

            // hack nights last Tuesday of the month (including January)
            $date->lastOfMonth(Carbon::TUESDAY)->setTime(18, 30);
            $dateStrings[] = $date->toIso8601String();

            $date = $date->copy()->startOfMonth()->addMonth();
        } while ((integer) $date->year === (integer) $year);

//        dd($dateStrings);
        foreach ($dateStrings as $string) {
            $this->info($string);
        }
    }
}