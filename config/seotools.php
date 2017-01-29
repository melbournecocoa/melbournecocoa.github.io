<?php

return [
    'meta'      => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'       => "Melbourne CocoaHeads Meetup", // set false to total remove
            'description' => 'This is the Melbourne chapter of the world-wide CocoaHeads group.
            Each month we gather to hear presentations on a diverse range of topics related to designing,
            developing and shipping applications for Apple\'s iOS and Mac OSX platforms. Melbourne CocoaHeads is
            organised by Jesse Collis',
            'separator'   => ' - ',
            'keywords'    => ['meetup', 'melbourne', 'teamsquare', 'apple', 'coworking', 'technology',
                'iOS', 'tvOS', 'OSX'],
            'author' => 'Jesse Collis'
        ],

        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
        ],
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => 'Melbourne CocoaHeads Meetup',
            'description' => 'This is the Melbourne chapter of the world-wide CocoaHeads group.
            Each month we gather to hear presentations on a diverse range of topics related to designing,
            developing and shipping applications for Apple\'s iOS and Mac OSX platforms. Melbourne CocoaHeads is
            organised by Jesse Collis',
            'url'         => false,
            'type'        => false,
            'site_name'   => 'Melbourne CocoaHeads',
            'images'      => [],
            'author' => 'Jesse Collis'
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
//            'card'        => 'summary',
            'site'        => '@melbournecocoa',
            'creator'     => '@sirjec'
        ],
    ],
];
