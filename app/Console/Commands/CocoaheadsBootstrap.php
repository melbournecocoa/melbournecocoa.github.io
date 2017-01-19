<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;

class CocoaheadsBootstrap extends Command
{

    protected $signature = 'cocoa:update {--bootstrap : Run 2016 events and sponsors. }';

    protected $description = 'Run events and posts update.';

    public function handle()
    {
        /**
         * Note: You don't need to call 2017 events unless you want to rewrite the events.yml file.
         */
        $this->call('cocoa:events-2016');
        $this->call('cocoa:events');
        $this->call('cocoa:posts');
    }
}