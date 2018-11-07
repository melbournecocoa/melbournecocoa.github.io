@extends('layouts.master')

@section('image', '/img/cch-events-bg.jpg')
@section('title', 'Melbourne CocoaHeads')
@section('subtitle')
    <span class="subheading">Calendar</span>
@endsection

@section('content')
    <article>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <h2>Event Calendar</h2>

                    <p>All the <a href="/events">events</a> are syndicated in an iCal feed that you can subscribe to. The best way to
                        subscribe is to add the following address as a new calendar subscription.</p>

                    <pre>{{ route('calendarFeed') }}</pre>

                    <div class="alert alert-info" role="alert">You can also <a style="text-decoration:underline;" href="{{ route('calendarFeed') }}">click here</a> to load the calendar url directly.</div>


                    <p>The keyboard shortcut in Calendar.app is <kbd>Command</kbd> <kbd>Option</kbd> <kbd>S</kbd></p>

                </div>
            </div>
        </div>
    </article>
@endsection