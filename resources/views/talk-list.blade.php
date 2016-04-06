@extends('layouts.master')

@section('image', '/img/cch-events-bg.jpg')
@section('title', 'Submitted Talks')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">

                @foreach($talks as $talk)

                <div class="panel panel-default">
                    <div class="panel-heading">{{ $talk->title }} for {{$talk->event->starts_at->format('F') }}</div>
                    <div class="panel-body">
                        <dl>
                            <dt>Title</dt>
                            <dd>{{ $talk->title }}</dd>
                            <dt>Description</dt>
                            <dd>{{ $talk->description }}</dd>
                            <dt>Format</dt>
                            <dd>{{ ucwords($talk->format) }}</dd>
                            <dt>Event</dt>
                            <dd><a href="{{ route('event', ['event' => $talk->event]) }}">{{ $talk->event->title }}</a></dd>

                            <dt>Name</dt>
                            <dd>{{ $talk->name }}</dd>

                            <dt>Twitter</dt>
                            <dd>{{ $talk->twitter }}</dd>

                            <dt>Slack</dt>
                            <dd>{{ $talk->slack }}</dd>

                            <dt>Email</dt>
                            <dd><a href="mailto:{{ $talk->email }}">{{ $talk->email }}</a></dd>

                            <dt>Extra</dt>
                            <dd>{{ $talk->extra }}</dd>

                            <dt>Submitted</dt>
                            <dd>{{ $talk->created_at}}</dd>
                        </dl>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection