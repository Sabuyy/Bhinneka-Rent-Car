<?php

return [
    'default' => 'main',
    'debug'   => false,
    'connections' => [
        'main' => [
            'adapter' => 'mysql',
            'options' => [
                'host'         => 'localhost',
                'port'         => '3306',
                'dbname'       => 'phalcon-master',
                'username'     => 'root',
                'password'     => '',
                'dialectClass' => Phalcon\Db\Dialect\Mysql::class,
            ]
        ],
    ],
];
