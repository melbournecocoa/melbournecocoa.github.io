@extends('layouts.master')

@section('image', '/img/cch-post-bg-filter.jpg')
@section('title', 'Melbourne Cocoaheads')
@section('subtitle')
    <span class="subheading">Updates</span>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                @foreach($posts as $post)
                    <div class="post-preview">
                        <a href="{{ $post->url() }}">
                            <h2 class="post-title">
                                {{ $post->title }}
                            </h2>
                            <h3 class="post-subtitle">
                                {{ $post->subtitle }}
                            </h3>
                        </a>
                        <p class="post-meta">Posted on {{ $post->created_at->format('l jS \\of F Y ') }}</p>
                    </div>
                    <hr>
                @endforeach

                <ul class="pager">
                    <li class="next {{ !$posts->hasMorePages() ? 'disabled' : '' }}">
                        <a href="{{ $posts->nextPageUrl() }}">Next Page &rarr;</a>
                    </li>
                    @if($posts->previousPageUrl())
                        <li class="previous">
                            <a href="{{ $posts->previousPageUrl() }}">&larr; Previous Page</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection