<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Carbon\Carbon;
use Illuminate\Support\Str;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(\App\Sponsor::class, function () {
    return [
        'name' => 'Teamsquare',
        'image' => 'sponsors/teamsquare.png',
        'web' => 'https://teamsquare.co',
        'twitter' => 'teamsquare'
    ];
});

$factory->define(\App\Event::class, function (Faker\Generator $faker) {

    $date = Carbon::createFromDate(2017, $faker->numberBetween(1, 12), null, new \DateTimeZone('Australia/Melbourne'));
    $date->nthOfMonth(2, Carbon::THURSDAY)->setTime(18, 30);

    $eventStart = $date->copy()->setTimezone(new \DateTimeZone('UTC'));
    $eventEnd = $eventStart->copy()->addHours(3);

    return [
        'title' => 'Melbourne CocoaHeads Meetup 2016',
        'subtitle' => 'Subtitle',
        'type' => \App\Event::MEETUP,
        'starts_at' => $eventStart,
        'ends_at' => $eventEnd,
        'location' => 'Teamsquare',
        'location_link' => 'https://teamsquare.co/contact',
        'address_display' => 'Level 1, 520 Bourke Street',
        'address' => 'Level 1, 520 Bourke Street, Melbourne, 3000',
        'lat' => -37.8153744,
        'lng' => 144.9562388,
        'tickets' => 'https://melbournecocoaheads2015.eventbrite.com.au',
        'contact' => 'jesse@jcmultimedia.com.au',
        'contact_name' => 'Jesse Collis'
    ];
});

$factory->define(\App\Post::class, function (Faker\Generator $faker) {

    $title = $faker->sentence();

    return [
        'title' => $title,
        'subtitle' => $faker->sentence(20),
        'slug' => Str::slug($title),
        'body' => $faker->realText()
    ];
});
