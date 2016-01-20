<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * Get the route key for the model.
     *
     * @return string
     */

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function url()
    {
        return route('post', $this->slug);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function scopeHomepagePosts($query)
    {
        return $query->where('frontpage', '>', 0)->orderBy('frontpage')->orderBy('created_at', 'desc');
    }
}
