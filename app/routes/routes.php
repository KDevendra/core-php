<?php

return [
    'web' => [
        '/' => 'HomeController@index',
        '/about' => 'HomeController@about',
        '/contact' => 'HomeController@contact',
        '/store' => 'HomeController@store',
    ],
    'api' => [
        '/api/users' => 'ApiController@getAllUsers',
        '/api/users/{id}' => 'ApiController@getUserById',
    ]
];
