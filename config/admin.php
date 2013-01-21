<?php
return [
    'name'              => 'Inposted::Admin',

    'defaultController' => 'admin',

    'controllerMap'     => [
        'error' => 'base\ErrorController',
    ],

    'components'        => [
        'errorHandler' => [
            'errorAction' => 'error',
        ],

        'clientScript' => [
            'packages' => [
                'main'      => [
                    'baseUrl' => 'static/css',
                    'css'     => ['inposted.css'],
                    'depends' => ['bootstrap'],
                ],
                'bootstrap' => [
                    'baseUrl' => 'static/bootstrap',
                    'css'     => ['css/bootstrap.css'],
                    'js'      => ['js/bootstrap.js'],
                    'depends' => ['jquery'],
                ],
            ],
        ],
    ],
];