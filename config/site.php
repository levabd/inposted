<?php
/** @var $name string Name of sub-application */
return [
    'name'       => 'Inposted',

    'import' => [
        'site.models.forms.*',
    ],

    'components' => [
        'geoip' => [
            'class' => 'site\components\GeoIp',
        ],

        'clientScript' => [
            'packages' => [
                'main'       => [
                    'baseUrl' => '',
                    'css'     => ['css/new.css'],
                    'depends' => ['bootstrap'],
                ],
//                'jquery'     => [
//                    'baseUrl' => 'static/js/',
//                    'js'      => ['jquery.min.js'],
//                ],
                'bootstrap'  => [
                    'baseUrl' => '',
                    'css'     => ['css/bootstrap.min.css', 'css/bootstrap-responsive.min.css'],
                    'js'      => ['js/bootstrap.min.js'],
                    'depends' => ['jquery'],
                ],
//                'angular'    => [
//                    'baseUrl' => 'static/angular',
//                    'js'      => [
//                        'angular.min.js',
//                        'angular-resource.min.js',
//                    ],
//                ],
//                'underscore' => [
//                    'baseUrl' => 'static/js/',
//                    'js'      => ['underscore.min.js', 'underscore.mixins.js'],
//                ],
//                'async'      => [
//                    'baseUrl' => 'static/js/',
//                    'js'      => ['async.min.js'],
//                ],
//                'prettify'   => [
//                    'baseUrl' => 'static/google-code-prettify',
//                    'js'      => ['prettify.min.js'],
//                    'css'     => ['prettify.min.css'],
//                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
];