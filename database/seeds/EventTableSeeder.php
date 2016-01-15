<?php

use App\Sponsor;
use Illuminate\Database\Seeder;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * @var $event \App\Event
         */
        $event = factory(\App\Event::class)->create();

        $sponsor = (new Sponsor)->first();
        $event->sponsors()->attach($sponsor);
        $event->save();
    }
}
