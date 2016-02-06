<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'name',
        'image',
        'web',
        'twitter'
    ];

    public function events()
    {
        $this->belongsToMany(Event::class, 'events_sponsors');
    }
}
