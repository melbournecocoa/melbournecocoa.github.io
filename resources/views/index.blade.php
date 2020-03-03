@extends('layouts.master')

<!-- Sponsors Banner -->

@section('image', '/img/cch-events-bg.jpg')
@section('title', 'Melbourne CocoaHeads')

@section('subtitle')
    @if($meetup)
        <p class="subheading">
            <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
            <a href="{{ $meetup->url }}" style="color:white">Next Meetup</a> &rarr; {{ $meetup->getFormattedTimeAttribute() }}
        </p>
        {{--<p class="subheading">--}}
            {{--<span class="glyphicon glyphicon-globe" aria-hidden="true"></span>--}}
            {{--{{ $event->location }} - {{ $event->address_display }}--}}
        {{--</p>--}}
    @else
        <p class="subheading">
            <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
            No meetups scheduled.
        </p>
    @endif

    @if($hackNight)
        <p class="subheading">
            <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
            <a href="{{ $hackNight->url }}" style="color:white">Next Hack Night</a> &rarr; {{ $hackNight->getFormattedTimeAttribute() }}
        </p>
        {{--<p class="subheading">--}}
            {{--<span class="glyphicon glyphicon-globe" aria-hidden="true"></span>--}}
            {{--{{ $hacknight->location }} - {{ $hacknight->address_display }}--}}
        {{--</p>--}}
    @else
        <p class="subheading">
            <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
            No upcoming Hack Nights scheduled.
        </p>
    @endif
@endsection


@section('content')
    <div class="container">
        <div class="row">
            <h1 class="text-center">Thank you to our Sponsors</h1>
            <p class="text-center"><b>Gold Sponsor</b> &nbsp; &nbsp;
                <a href="https://www.ittybittyapps.com"><img src="/img/sponsors/IttyBittyApps.png" height="128px" class="mr-3" alt="IttyBittyApps"></a>&nbsp;&nbsp;<a href="https://www.ittybittyapps.com">Itty Bitty Apps</a>.</p>
        </div>
        <div class="row">
            <p class="text-center"><b>Silver Sponsors</b></p>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="row text-center">
                        <a href="https://www.latitudefinancial.com.au">
                            <img src="/img/sponsors/Latitude_Financial_Services_Logo.png" height="96px" class="mr-3" alt="Latitude Financial Services">
                        </a>
                    </div>
                    <div class="row text-center">
                        <a href="https://www.latitudefinancial.com.au">Latitude Financial Services</a>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="row text-center">
                        <a href="https://www.cognizant.com/en-au">
                            <img src="/img/sponsors/Cognizant-logo.png" height="48px" class="mr-3" alt="Cognizant">
                        </a>
                    </div>
                    <div class="row text-center">
                        <a href="https://www.cognizant.com/en-au">Cognizant Australia</a>.
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="row text-center">
                        <a href="https://realestate.com.au">
                            <img src="/img/sponsors/realestate.jpg" height="64px" class="mr-3" alt="REA Group">
                        </a>
                    </div>
                    <div class="row text-center">
                        <a href="https://realestate.com.au">REA Group Ltd</a>.
                    </div>
                </div>
            </div>
        </div>

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
                        <p class="post-meta">Posted on {{ $post->created_at->format('l jS \\of F Y') }}</p>
                    </div>
                    <hr>
                @endforeach

                <ul class="pager">
                    <li class="next">
                        <a href="{{ route('posts') }}">All Updates &rarr;</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
