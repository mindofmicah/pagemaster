<?php
return [
    'view_directory' => 'pages',
    'route_action'   => 'MindOfMicah\PageMaster\PageMasterController@show',

    // List of views to ignore from the routes, for views within directories, use Laravel's dot notation
    'exclude'        => [],

    //Routing rules to apply to the group of routes
    'middleware'     => '',
    'as'             => '',
    'prefix'         => '',
];