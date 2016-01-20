<?php

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
        factory(\App\Post::class, 3)->make()->each(function (\App\Post $post) use (&$i) {
            $event = factory(\App\Event::class)->create();
            $post->event()->associate($event);
            $post->frontpage = ($i % 3);
            $post->save();
            $i++;
        });
    }
}
