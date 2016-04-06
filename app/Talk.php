<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Talk extends Model
{
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
