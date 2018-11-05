<?php

namespace App\Http\Resources;

use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class Event extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'startDate' => $this->starts_at->format(DateTime::ATOM),
            'address' => [
                'display' => $this->address_display,
                'location' => ['latitude' => $this->lat, 'longitude' => $this->lng],
            ],
            'links' => [
                'tickets' => $this->tickets ??  null,
                'location' => $this->location_link,
                'self' => route('events-api-event', $this->resource),
                'web' => route('event', $this->resource),
            ]
        ];
    }
}
