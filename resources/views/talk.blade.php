@extends('layouts.master')

@section('image', '/img/cch-events-bg.jpg')
@section('title', 'Submit a talk')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">

            @if (count($errors) > 0)
                <div class="panel panel-danger">
                    <div class="panel-heading">There was an issue...</div>
                    <div class="panel-body">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

                <form method="post" action="{{route('submitTalk')}}">

                    {{ csrf_field() }}

                    <div class="form-group {{$errors->get('title') ? 'has-error' : '' }}">
                        <label for="inputTalkTitle">Working title of your talk</label>
                        <input type="text" class="form-control" id="inputTalkTitle" name="title" value="{{ old('title') }}">
                    </div>
                    <div class="form-group {{$errors->get('description') ? 'has-error' : '' }}">
                        <label for="inputTalkDescription">A rough description</label>
                        <textarea class="form-control" rows="3" id="inputTalkDescription" name="description">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="formatRadios1">Talk format</label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="format" id="formatRadios1" value="full" {{ old('format') !== 'lightning' ? 'checked' : '' }}>
                                Full presentation (~20 minutes)
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="format" id="formatRadios2" value="lightning" {{ old('format') === 'lightning' ? 'checked' : '' }}>
                                Lightning Talk (~5 minutes)
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Which event?</label>
                        <select class="form-control" name="event">
                            <option value="">Select an upcoming event</option>
                            @foreach($events as $event)
                                <option value="{{$event->id}}" {{ old('event') == $event->id ? 'selected="selected"' : '' }}>{{$event->title}} @ {{$event->starts_at->format('d/m/Y')}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group {{$errors->get('name') ? 'has-error' : '' }}">
                        <label for="inputName">Your name</label>
                        <input type="text" class="form-control" id="inputName" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="form-group {{$errors->get('email') ? 'has-error' : '' }}">
                        <label for="inputEmail">Email</label>
                        <input type="email" class="form-control" id="inputEmail" name="email" value="{{ old('email') }}">
                        <span id="helpBlock" class="help-block small">Email address won't be be published, it's just so I can get in touch.</span>
                    </div>
                    <div class="form-group">
                        <label for="inputTwitter">Twitter (optional)</label>
                        <input type="text" class="form-control" id="inputTwitter" name="twitter" placeholder="@melbournecocoa" value="{{ old('twitter') }}">
                    </div>
                    <div class="form-group">
                        <label for="inputSlack">Username on melbournecocoa slack (optional)</label>
                        <input type="text" class="form-control" id="inputSlack" name="slack" placeholder="@username" value="{{ old('slack') }}">
                    </div>
                    <div class="form-group">
                        <label for="inputTalkExtra">Additional comments, notes or instructions</label>
                        <textarea class="form-control" rows="3" id="inputTalkExtra" name="extra">{{ old('extra') }}</textarea>
                    </div>

                    {{--<div class="form-group">--}}
                        {{--<label for="inputTalkCaptcha">What day of the week does Melbourne Cocoaheads meet?</label>--}}
                        {{--<input type="text" class="form-control" id="inputCaptcha" name="captcha" placeholder="The day after Wednesday">--}}
                    {{--</div>--}}

                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
            </div>
        </div>
    </div>

@endsection