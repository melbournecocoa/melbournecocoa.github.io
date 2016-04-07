@extends('layouts.email')

@section('title', '[CCH] Talk submitted:' . $talk->title)

@section('content')

<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" class="responsive-table">
    <tr>
        <td>
            <!-- HERO IMAGE -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <!-- COPY -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="center" style="font-size: 25px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;" class="padding">{{ $talk->title }}</td>
                            </tr>
                            <tr>
                                <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding">
                                    <strong>Description: </strong>{{ $talk->description }}<br>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding">
                                    Submitted by {{ $talk->name }}<br>
                                    <strong>Email:</strong> <a href="mailto:{{ $talk->email }}">{{ $talk->email }}</a>
                                    @if($talk->twitter)<br><strong>Twitter:</strong> <a href="https://www.twitter.com/{{$talk->twitter}}">{{$talk->twitter}}</a>@endif
                                    @if($talk->slack)<br><strong>Slack:</strong> {{ $talk->slack }}@endif
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding">
                                    <strong>Type:</strong> {{ucwords($talk->format)}}<br>
                                    <strong>When:</strong> {{$talk->event->title}} @ {{$talk->event->starts_at->format('d/m/Y')}}
                                </td>
                            </tr>
                            @if($talk->extra)
                            <tr>
                                <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding">
                                    <strong>Extra Notes</strong><br>
                                    {{ $talk->extra }}
                                </td>
                            </tr>
                            @endif
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <!-- BULLETPROOF BUTTON -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="center" style="padding-top: 25px;" class="padding">
                                    <table border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                        <tr>
                                            <td align="center" style="border-radius: 3px;" bgcolor="#256F9C"><a href="https://www.melbournecocoaheads.com/talks/list" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; border-radius: 3px; padding: 15px 25px; border: 1px solid #256F9C; display: inline-block;" class="mobile-button">List Talks</a></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@endsection