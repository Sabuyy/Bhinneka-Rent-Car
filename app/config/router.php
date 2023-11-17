<?php

return [

    /**
     * Allow reading route definition from controller annotation
     */
    'annotationRoute' => [
        'enabled'       => true,
        'namespace'     => 'App\\Modules',
        'directory'     => APP_PATH . '/Modules',
        'isModular'     => true,
        'defaultModule' => 'Defaults',
        'modules'       => [
            'Chpn'      => 'chpn',
            'Panel'     => 'panel',
            'Demo'      => 'demo',
            'Asasta'    => 'asasta',
            'Tkr'       => 'tkr',
            'Tjm'       => 'tjm',
            'Tlh'       => 'tlh',
            'Twm'       => 'twm',
            'Smd'       => 'smd'
        ],
    ],

    /**
     * Route loaded in folder app/routes
     */
    'routes' => ['main'],
];