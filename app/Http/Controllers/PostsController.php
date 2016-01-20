<?php

namespace App\Http\Controllers;

use App\Event;
use App\Post;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{
    public function getHome()
    {
        $posts = (new Post)->homepagePosts()->get();

        $event = (new Event)->nextEvent()->first();

        return view('index', ['posts' => $posts, 'event' => $event]);
    }

    public function getPosts()
    {
        $posts = Post::paginate(2);

        return view('posts', ['posts' => $posts]);
    }

    public function getSinglePost(Post $post)
    {
        return view('post', ['post' => $post]);
    }
}
