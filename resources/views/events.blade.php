@extends('layouts.master')

@section('header')
    <header class="intro-header" style="background-image: url('/img/home-bg.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>Melbourne Cocoaheads</h1>
                        <hr class="small">
                        <span class="subheading">{{ $title }}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                @foreach($events as $event)
                    <div class="post-preview">
                        <a href="{{ $event->url() }}">
                            <h2 class="post-title">
                                {{ $event->title }}
                            </h2>
                        </a>
                        <p>
                            {{ $event->subtitle }}
                        </p>

                        <h3>When &amp; Where</h3>

                        <p>
                            <span class="glyphicon glyphicon-calendar"></span> {{ $event->formattedTime }}
                        </p>

                        <address>
                            <strong>{{ $event->location }}</strong><br>
                            {{ $event->address_display }}<br>
                        </address>

                        <ul class="list-inline">
                            <li>
                                <i class="fa fa-globe"></i> <a href="{{ $event->location_link }}">Venue</a>
                            </li>

                            <li>
                                <span class="glyphicon glyphicon-map-marker"></span> <a href="http://maps.google.com/?q={{ $event->address }}">Map</a>
                            </li>
                        </ul>

                        @if(!$event->sponsors->isEmpty())
                        <h3>Sponsors</h3>

                            @foreach($event->sponsors as $sponsor)
                                <a href="{{ $sponsor->web }}"><img src="/img/{{ $sponsor->image }}" alt="{{$sponsor->name}}" class="img-responsive"></a>
                            @endforeach

                        @endif

                        @if($event->tickets)
                            <h3>Tickets</h3>

                            <p>
                            <ul class="list-inline">
                                <li>
                                    <i class="fa fa-ticket"></i> <a href="{{ $event->tickets }}">Tickets</a>
                                </li>
                            </ul>
                            </p>
                        @endif

                        @if(!$event->posts->isEmpty())
                            <h3>Updates</h3>

                            <ul>
                                @foreach($event->posts as $post)
                                    <li><a href="{{ $post->url() }}">{{ $post->title }}</a></li>
                                @endforeach
                            </ul>
                        @endif

                        <p class="post-meta">
                            This event is organised by <a href="{{$event->contact}}">{{ $event->contact_name }}</a>
                        </p>
                    </div>
                    <hr>
                @endforeach

                @if($events->total() < 1)
                    <div class="post-preview">
                        <p>There are no events scheduled.</p>
                    </div>
                @endif


                <ul class="pager">
                    <li class="next {{ !$events->hasMorePages() ? 'disabled' : '' }}">
                        <a href="{{ $events->nextPageUrl() }}">More Events &rarr;</a>
                    </li>
                    @if($events->previousPageUrl())
                        <li class="previous ">
                            <a href="{{ $events->previousPageUrl() }}">&larr; Previous Events</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection