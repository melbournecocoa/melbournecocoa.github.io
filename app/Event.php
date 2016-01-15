<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $dates = ['created_at', 'updated_at', 'starts_at', 'ends_at'];

    public function post()
    {
        return $this->hasOne(Post::class);
    }

    public function sponsors()
    {
        return $this->belongsToMany(Sponsor::class, 'events_sponsors');
    }
}
