@extends('layouts.master')

@section('image', '/img/cch-post-bg-filter.jpg')
@section('title', $post->title)
@section('subtitle')
    <span class="subheading">{{ $post->subtitle }}</span>
@endsection

@section('content')
    <article>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    {!! $post->body !!}
                </div>
            </div>
             @if(!$post->events->isEmpty())
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <h2 class="section-heading">Related Events</h2>
                    <ul>
                        @foreach($post->events as $event)
                            <li><a href="{{ $event->url }}">{{ $event->title }}</a> - <span class="small">{{ $event->getFormattedTimeAttribute() }}</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <p class="post-meta">Posted on {{ $post->created_at->format('l jS \\of F Y ') }}</p>
                </div>
            </div>
        </div>
    </article>
@endsection
