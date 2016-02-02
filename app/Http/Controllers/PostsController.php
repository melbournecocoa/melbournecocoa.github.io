<?php

namespace App\Http\Controllers;

use App\Event;
use App\Post;
use SEO;

class PostsController extends Controller
{
    public function getHome()
    {
        $posts = (new Post)->homepagePosts()->get();

        SEO::setTitle('Melbourne Cocoaheads');
        SEO::opengraph()->setUrl(request()->fullUrl());
        SEO::opengraph()->addProperty('type', 'articles');

        $event = (new Event)->nextEvent()->first();
        $hacknight = (new Event)->nextHacknight()->first();

        return view('index', ['posts' => $posts, 'event' => $event, 'hacknight' => $hacknight]);
    }

    public function getPosts()
    {
        $posts = Post::paginate(5);

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
}
