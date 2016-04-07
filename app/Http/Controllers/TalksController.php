<?php

namespace App\Http\Controllers;

use App\Talk;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Mail;

class TalksController extends Controller
{
    public function listTalks()
    {
        $talks = Talk::orderBy('created_at')->get();

        return view('talk-list', ['talks' => $talks]);
    }

    public function submitTalk(Request $request)
    {
        $this->validate($request, [
           'title' => 'required',
            'description' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'event' => 'required|numeric',
//            'captcha' => 'required|in:thursday'
        ]);

        $talk = new Talk();
        $talk->title = $request->input('title');
        $talk->description = $request->input('description');
        $talk->extra = $request->input('extra');
        $talk->format = $request->input('format');
        $talk->name = $request->input('name');
        $talk->email = $request->input('email');
        $talk->event_id = $request->input('event');
        $talk->twitter = $request->input('twitter');
        $talk->slack = $request->input('slack');

        $talk->save();

        $admin = User::where('email', '=', env('ADMIN_EMAIL'))->first();

        $subject = '[CCH] Talk submitted: ' . $talk->title;

        Mail::send('emails.talk', ['talk' => $talk], function (Message $m) use ($talk, $admin, $subject) {
            $m->to($admin->email);
            $m->replyTo($talk->email);
            $m->subject($subject);
        });

        return redirect()->route('submitTalkSuccess');
    }
}
