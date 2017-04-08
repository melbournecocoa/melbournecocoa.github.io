<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    const HACKNIGHT = 'hacknight';
    const MEETUP = 'meetup';
    const SPECIAL = 'special';

    protected $dates = [
        'created_at',
        'updated_at',
        'starts_at',
        'ends_at'
    ];

    protected $fillable = [
        'slug'
    ];

    protected $hidden = [
        'slug',
        'created_at',
        'updated_at',
        'id',
    ];

    protected $appends = [
      'url',
    ];

    protected $casts = [
        'lat' => 'double',
        'lng' => 'double',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'events_posts');
    }

    public function sponsors()
    {
        return $this->belongsToMany(Sponsor::class, 'events_sponsors');
    }

    public function eventNumber() : int
    {
        return static::eventNumberDate($this->starts_at);
    }

    public static function eventNumberDate(Carbon $starts_at) : int
    {
        return 90 + (($starts_at->year - 2016) * 11) + ($starts_at->month - 1);
    }

    public function getFormattedTimeAttribute()
    {
        $tz = new \DateTimeZone('Australia/Melbourne');

        $sa = $this->starts_at->copy()->setTimeZone($tz);
        $ea = $this->ends_at->copy()->setTimeZone($tz);

        return $sa->format('D jS \\of M Y \\@ g:i') . ' - '. $ea->format('g:i A');
    }

    public function getUrlAttribute()
    {
        return route('event', $this->slug);
    }

    /**
     * Returns next scheduled event after this one
     */
    public function followingEvent()
    {
        return Event::where('starts_at', '>=', $this->ends_at)
            ->where('type', '=', Event::MEETUP)
            ->first();
    }

    /**
     * Next HackNight after this one
     */
    public function followingHacknight()
    {
        return Event::where('starts_at', '>=', $this->ends_at)
            ->where('type', '=', Event::HACKNIGHT)
            ->first();
    }

    public function scopeNextEvent($query)
    {
        return $query->where('type', '=', Event::MEETUP)
            ->where('ends_at', '>=', Carbon::now(new \DateTimeZone('UTC')))
            ->orderBy('starts_at', 'asc')
            ->limit(1);
    }

    public function scopeNextHacknight($query)
    {
        return $query->where('type', '=', Event::HACKNIGHT)
            ->where('ends_at', '>=', Carbon::now(new \DateTimeZone('UTC')))
            ->orderBy('starts_at', 'asc')
            ->limit(1);
    }

    public function scopeUpcomingEvents($query)
    {
        return $query->where('ends_at', '>=', Carbon::now())
            ->orderBy('starts_at');
    }

    public function formattedSponsorNames()
    {
        return implode(' and ', array_map(function ($s) {
            return $s->name;
        }, $this->sponsors->all()));
    }
}
