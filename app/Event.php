<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    const HACKNIGHT = 'hacknight';
    const MEETUP = 'meetup';

    protected $dates = ['created_at', 'updated_at', 'starts_at', 'ends_at'];

    public function url()
    {
        return route('event', $this->id);
    }

    public function post()
    {
        return $this->hasOne(Post::class);
    }

    public function sponsors()
    {
        return $this->belongsToMany(Sponsor::class, 'events_sponsors');
    }

    public function getFormattedTimeAttribute()
    {
        $tz = new \DateTimeZone('Australia/Melbourne');

        $sa = $this->starts_at->copy()->setTimeZone($tz);
        $ea = $this->ends_at->copy()->setTimeZone($tz);

        return $sa->format('l jS \\of F Y \\@ g:i') . ' - '. $ea->format('g:i A');
    }

    public function scopeNextEvent($query)
    {
        return $query->where('type', '=', EVent::MEETUP)
            ->where('ends_at', '>=', Carbon::now(new \DateTimeZone('UTC')))
            ->orderBy('starts_at')
            ->limit(1);
    }
}
