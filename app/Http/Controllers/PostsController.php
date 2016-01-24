<?php

namespace App\Http\Controllers;

use App\Event;
use App\Post;

class PostsController extends Controller
{
    public function getHome()
    {
        $posts = (new Post)->homepagePosts()->get();

        $event = (new Event)->nextEvent()->first();
        $hacknight = (new Event)->nextHacknight()->first();

        return view('index', ['posts' => $posts, 'event' => $event, 'hacknight' => $hacknight]);
    }

    public function getPosts()
    {
        $posts = Post::paginate(5);

        return view('posts', ['posts' => $posts]);
    }

    public function getSinglePost(Post $post)
    {
        return view('post', ['post' => $post]);
    }
}
