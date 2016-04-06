<?php

namespace App\Http\Controllers;

use App\Talk;
use Illuminate\Http\Request;

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

        return redirect()->route('submitTalkSuccess');
    }
}
