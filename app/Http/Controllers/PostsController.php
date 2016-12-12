<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests\Request;
use App\Post;
use Feed;
use SEO;

class PostsController extends Controller
{
    public function getHome()
    {
        $posts = (new Post)->homepagePosts()->get();
        $event = (new Event)->nextEvent()->first();
        $hacknight = (new Event)->nextHacknight()->first();
        $post = $posts->first();

        if ($event) {
            $description = <<<EOT
Melbourne Cocoaheads - Next Meetup {$event->getFormattedTimeAttribute()}. Next Hacknight {$hacknight->getFormattedTimeAttribute()}.
EOT;
        } else {
            $description = "Melbourne Cocoaheads";
        }

        SEO::setTitle('Melbourne Cocoaheads');
        SEO::setDescription($description);
        SEO::opengraph()->setUrl(request()->fullUrl());
        SEO::opengraph()->setArticle([
            'published_time' => $post->created_at,
            'modified_time' => $post->modified_at,
            'author' => 'Jesse Collis',
            'section' => 'updates',
            'tag' => ['updates']
        ]);

        SEO::opengraph()->addProperty('locale', 'en-au');
        if ($post->coverImage) {
            SEO::addImages([url($post->coverImage)]);
        }

        return view('index', ['posts' => $posts, 'event' => $event, 'hacknight' => $hacknight]);
    }

    public function getPosts()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(5);

        SEO::setTitle('Melbourne Cocoaheads');
        SEO::opengraph()->setUrl(request()->fullUrl());
        SEO::opengraph()->addProperty('type', 'articles');

        return view('posts', ['posts' => $posts]);
    }

    public function getSinglePost(Post $post)
    {
        SEO::setTitle("$post->title - Melbourne Cocoaheads");
        SEO::setDescription($post->subtitle);
        SEO::opengraph()->setUrl($post->url());
        SEO::opengraph()->setArticle([
            'published_time' => $post->created_at,
            'modified_time' => $post->modified_at,
            'expiration_time' => 'datetime',
            'author' => 'Jesse Collis',
            'section' => 'updates',
            'tag' => ['updates']
        ]);

        SEO::opengraph()->addProperty('locale', 'en-au');
        if ($post->coverImage) {
            SEO::addImages([url($post->coverImage)]);
        }

        return view('post', ['post' => $post]);
    }

    public function feed()
    {
        $feed = Feed::make();

        // cache the feed for 60 minutes (second parameter is optional)
        $feed->setCache(60, 'laravelFeedKey');

        // check if there is cached feed and build new only if is not
        if (!$feed->isCached()) {
            // creating rss feed with our most recent 20 posts

            $posts = Post::orderBy('created_at')->take(20)->get();

            // set your feed's title, description, link, pubdate and language
            //FIXME: These are duplicated

            $feed->title = config('seotools.meta.defaults.title');
            $feed->description = config('seotools.meta.defaults.description');
            $feed->logo = 'http://yoursite.tld/logo.jpg';
            $feed->link = route('feed');
            $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
            $feed->pubdate = $posts[0]->created_at;
            $feed->lang = 'en';
            $feed->setShortening(true); // true or false
            $feed->setTextLimit(200); // maximum length of description text

            foreach ($posts as $post) {
                // set item's title, author, url, pubdate, description and content

                $image = null;
                if ($post->coverImage) {
                    $image = ['url' => url($post->coverImage), 'type'=>'image/jpeg'];
                }

                $feed->add(
                    $post->title,
                    'Jesse Collis',
                    $post->url(),
                    $post->created_at,
                    $post->subtitle,
                    $post->body,
                    $image
                );
            }
        }

        return $feed->render('atom');
    }
}
