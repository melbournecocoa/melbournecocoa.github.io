<?php

return [
    'meta'      => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'       => 'Melbourne CocoaHeads Meetup', // set false to total remove
            'description' => 'Melbourne CocoaHeads has been running monthly since 2007 and is Melbourne’s longest running independent 
            Apple developer community event. We\'re run by a small group of volunteers and supported by the wider Melbourne community. 
            Each month members volunteer their time to present on iOS, macOS and software development topics.',
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
            'description' => 'Melbourne CocoaHeads has been running monthly since 2007 and is Melbourne’s longest running independent 
             Apple developer community event. We\'re run by a small group of volunteers and supported by the wider Melbourne community. 
             Each month members volunteer their time to present on iOS, macOS and software development topics.',
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
