@extends('layouts.master')

@section('header')
        <header class="intro-header" style="background-image: url('/img/cch-post-bg-filter.jpg')">
            {{--{{ $post->image }}--}}
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        {{--<h1>{{ $post->title }}</h1>--}}
                        <h1>Melbourne Cocoaheads 2015 End of Year Meetup (#90)</h1>
                        <hr class="small">
                        <span class="subheading">Celebrating 9 years of meetups!</span>
                        {{--{{ $post->subtitle }}--}}
                    </div>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <article>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <h2>2015 has been a really exciting year!</h2>

                    <p>Some of my highlights include: </p>

                    <p>
                        <ul>
                            <li><i>Graduating</i> from our humble RMIT AV studio into the palatial surrounds of Teamsquare.</li>
                            <li>Engaging 9 different Melbourne based sponsors.</li>
                            <li>Live streaming events on YouTube.</li>
                            <li>Consuming over 200 pizzas.</li>
                            <li>RSVP-ing 730 times.</li>
                            <li>Hosting over 40 presentations and lightning talks.</li>
                            <li>Wrapping up our <a href="https://github.com/melbournecocoa/MelbourneCocoaheadsHistory/blob/master/2007.mdown">9th year of meetups</a>!</li>
                        </ul>
                    </p>

                    <img class="img-responsive" src="/img/posts/cocoaheads-2015-12-cover.jpg" alt="Melbourne Cocoaheads 2015 End of Year Meetup">

                    <h2 class="section-heading">Yeah okay, but who's speaking this month?</h2>

                    <p>This month we're going to skip the formalities and presentations - we'd like you to get up and say hi.</p>

                    <p>Every month there are are more and more new faces coming to cocoaheads, new companies in Melbourne
                        and surrounds doing amazing stuff and we'd like as many people as possible to stand up, say hi,
                        say who you are, (maybe who you work for) and what you're working on.</p>

                    <p>In my mind we'll spend some time before pizzas handing around the microphone and everyone can take
                        it in turns hopping up and giving a 30 second spiel on themselves or give a quick app demo if
                        it's relevant. It would be cool if you let us know who you are, what you're working on, what you're
                        interested in or just why you come to cocoaheads. (If you're worried about this impending public
                        speaking situation or need some ideas or encouragement, let Pete and/or I know and we can give
                        you ideas ahead of time.)</p>

                    <p>Melbourne Cocoaheads is the <strong>top</strong> Cocoa based community across the globe, and is
                        known for being the <strong>friendliest</strong>, fastest growing, <strong>highest quality</strong>
                        presentation holding, best beverage providing meetup ever. <strong>Awesome people like you</strong>
                        make this fact, so please come this month and share a little about yourself.</p>

                    <p>We (Pete and Jesse) will put together a slide for each person, so just shoot through your details to Jesse and/or Pete:</p>

                    <ul>
                        <li>Your name</li>
                        <li>Who you work for / What you work on / What you study</li>
                        <li>Twitter / github handle</li>
                    </ul>

                    <p> Our details are: </p>

                    <p>
                        <ul>
                            <li>Twitter: @_petegoldsmith @sirjec</li>
                            <li>Email: <a href="mailto:peter@ittybittyapps.com">peter@ittybittyapps.com</a> <a href="mailto:jesse@jcmultimedia.com.au">jesse@jcmultimedia.com.au</a></li>
                            <li>Slack: @pete @jesse (<a href="http://melbournecocoa.slack.com/">melbournecocoa.slack.com</a>)</li>
                        </ul>
                    </p>

                    <p class="post-meta">Posted on {{ Carbon\Carbon::now()->format('l jS \\of F Y ') }}</p>
                </div>
            </div>
        </div>
    </article>

@endsection