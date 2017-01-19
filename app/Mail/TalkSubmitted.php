<?php

namespace App\Mail;

use App\Talk;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TalkSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Talk
     */
    public $talk;

    public function __construct(Talk $talk)
    {
        $this->talk = $talk;
    }

    public function build()
    {
        return $this->replyTo($this->talk->email)
            ->subject("[CCH] Talk submitted: {$this->talk->title}")
            ->view('emails.talk');
    }
}
