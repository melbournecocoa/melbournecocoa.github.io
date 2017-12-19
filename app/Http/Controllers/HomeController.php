<?php


namespace App\Http\Controllers;

use App\Event;
use App\Post;
use Illuminate\Database\Eloquent\Collection;
use SEO;

class HomeController extends Controller
{
    public function index()
    {
        /**
         * @var Collection
         */
        $posts = Post::homepagePosts()->get();
        /**
         * $var $event Event
         */
        $event = Event::nextEvent()->first();
        $hackNight = Event::nextHacknight()->first();

        $post = $posts->first();

        $description = "Melbourne CocoaHeads";

        if ($event && $hackNight) {
            $hnt = $hackNight->getFormattedTimeAttribute();
            $mut = $event->getFormattedTimeAttribute();

            $description = "Melbourne CocoaHeads - Next Meetup {$mut}. Next Hack Night {$hnt}.";
        }

        SEO::setTitle('Melbourne CocoaHeads');
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

        $data = [
            'posts' => $posts,
            'meetup' => $event,
            'hackNight' => $hackNight
        ];

        return view('index', $data);
    }
}
