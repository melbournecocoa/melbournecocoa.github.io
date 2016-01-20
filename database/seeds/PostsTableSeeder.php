<?php

use App\Sponsor;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i = 0;
        factory(\App\Post::class, 5)->create()->each(function (\App\Post $post) use (&$i) {
            $event = factory(\App\Event::class)->create();

            $sponsor = (new Sponsor)->first();
            $event->sponsors()->attach($sponsor);

            $post->events()->attach($event);
            $post->frontpage = ($i == 1) ? 1 : 0;
            $post->save();
            $i++;
        });
    }
}
