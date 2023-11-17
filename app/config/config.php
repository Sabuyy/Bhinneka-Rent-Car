<?php

return [
    'appName'      => 'ANGGARAN',
    'appLongName'  => 'Sistem Informasi Anggaran',
    'appDescription' => 'Sistem Informasi Kepegawaian Aanggaran Phalcon 4 Boilerplate',
    'appVersion'     => 'v2.0',

    'timezone' => 'Asia/Jakarta',

    'annotationRoute'   => true,
    'enable_cache' => false,

    'view' => [
        'autoescape' => false,
        'directory'  => BASEPATH . '/app/views',
        'always'     => true,
        'path'       => BASEPATH . '/storage/cache/view',
    ],

    'session' => [
        'savePath' => BASEPATH . '/storage/session',
        'prefix'   => 'PQSESS_',
        'uniqid'   => 'PQSESSID',
    ],

    'cache' => [
        'lifetime'         => 3 * 3600,
        'viewCache'        => BASEPATH . '/storage/cache/views',
        'dataCache'        => BASEPATH . '/storage/cache/data',
        'annotationsCache' => BASEPATH . '/storage/cache/annotations',
    ],

    'middlewares' => [
        // Controller::class,
    ],
];
