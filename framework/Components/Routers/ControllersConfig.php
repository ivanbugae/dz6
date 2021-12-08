<?php
return [
    '' => [
        'path' => '/',
        'method' => 'main',
        'controller' => \app\Controllers\Index::class
    ],
    'Page' => [
        'path' => '/page',
        'method' => 'view',
        'controller' => \app\Controllers\Page::class
    ],
    'NotFound' => [
        'method' => 'NotFound',
        'controller' => \app\Controllers\NotFound::class
    ]
];