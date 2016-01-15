<?php

use Illuminate\Database\Seeder;
use App\Event;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * @var $post \App\Post
         */
        $post = factory(\App\Post::class)->make();

        $event = (new Event)->first();
        $post->event()->associate($event);

        $post->save();
    }
}
