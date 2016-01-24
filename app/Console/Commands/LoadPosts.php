<?php

namespace App\Console\Commands;

use App\Event;
use App\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Parser;

class LoadPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cocoa:posts {file=posts.yml : the yaml file to load}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load posts from a YAML file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $yaml = new Parser();

        $posts = $yaml->parse(file_get_contents(base_path($this->argument('file'))));

        foreach ($posts['posts'] as $post) {

            $slug = Str::slug($post['title']);

            $newPost = Post::firstOrNew(['slug' => $slug]);
            $newPost->timestamps = false;

            $newPost->title = $post['title'];
            $newPost->subtitle = $post['subtitle'];
            $newPost->body = $post['body'];
            $newPost->frontpage = $post['frontpage'];

            $newPost->created_at = $post['created_at'];
            $newPost->updated_at = Carbon::now();

            $newPost->save();

            $newPost->events()->detach();

            if (isset($post['events']) && is_array($post['events'])) {
                $events = (new Event)->whereIn('id', $post['events'])->get();

                if (!$events->isEmpty()) {
                    $newPost->events()->saveMany($events);
                }
            }

            $this->info("Loaded: $newPost->title");
        }
    }
}
