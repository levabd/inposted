<?php
/** @var $name string Name of sub-application */
return [
    'name'       => 'Inposted',

    'components' => [
        'clientScript' => [
            'packages' => [
                'main'       => [
                    'baseUrl' => 'static/css',
                    'css'     => ['sirv.css', 'sirv-animations.css', 'sirv-prettify.css'],
                    'depends' => ['bootstrap'],
                ],
                'jquery'     => [
                    'baseUrl' => 'static/js/',
                    'js'      => ['jquery.min.js'],
                ],
                'bootstrap'  => [
                    'baseUrl' => 'static/bootstrap',
                    'css'     => ['css/bootstrap.min.css'],
                    'js'      => ['js/bootstrap.min.js'],
                    'depends' => ['jquery'],
                ],
                'angular'    => [
                    'baseUrl' => 'static/angular',
                    'js'      => [
                        'angular.min.js',
                        'angular-resource.min.js',
                    ],
                ],
                'underscore' => [
                    'baseUrl' => 'static/js/',
                    'js'      => ['underscore.min.js', 'underscore.mixins.js'],
                ],
                'async'      => [
                    'baseUrl' => 'static/js/',
                    'js'      => ['async.min.js'],
                ],
                'prettify'   => [
                    'baseUrl' => 'static/google-code-prettify',
                    'js'      => ['prettify.min.js'],
                    'css'     => ['prettify.min.css'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
];