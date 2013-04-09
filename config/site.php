<?php
/** @var $name string Name of sub-application */

$js = function ($script, $min = null) {
    null === $min && $min = !YII_DEBUG;
    return $min ? "$script.min.js" : "$script.js";
};

return [
    'name'          => 'Inposted',

    'controllerMap' => [
        'go' => 'site\components\urlShorten\Controller',
    ],

    'behaviors'     => ['site\behaviors\Application'],

    'components'    => [
        'geoip'        => [
            'class' => 'site\components\GeoIp',
        ],

        'urlShorten'   => [
            'class' => 'site\components\urlShorten\UrlShorten',
        ],

        'clientScript' => [
            'packages' => [
                'app'                  => [
                    'baseUrl' => 'js/app',
                    'js'      => ['app.js', 'directives.js', 'services.js', 'controllers.js', 'settings.js?path=' . trim($_SERVER['REQUEST_URI'], '/')],
                ],

                'main'                 => [
                    'baseUrl' => '',
                    'js'      => [$js('js/viewport/jquery.viewport')],

                    'css'     => ['css/new.css'],
                    'depends' => ['bootstrap', 'angular', 'angular-ui-bootstrap', 'underscore', 'app'],
                ],

//                'jquery'     => [
//                    'baseUrl' => '',
//                    'js'      => ['js/jquery.min.js'],
//                ],
                'bootstrap'            => [
                    'baseUrl' => '',
                    'css'     => ['css/bootstrap.min.css'],
                    'js'      => [$js('js/bootstrap')],
                    'depends' => ['jquery'],
                ],
                'angular'              => [
                    'baseUrl' => 'js/angular',
                    'js'      => [
                        $js('angular'),
                        $js('angular-resource'),
                        $js('angular-sanitize'),
                    ],
                ],
                'angular-ui'           => [
                    'basePath' => 'vendors.angular-ui.build',
                    'css'      => ['angular-ui.min.css'],
                    'js'       => [$js('angular-ui')],
                ],
                'angular-ui-bootstrap' => [
                    'basePath' => 'vendors.angular-ui-bootstrap',
                    'js'       => [$js('ui-bootstrap-tpls-0.2.0')],
                ],

                'select2'              => [
                    'basePath' => 'vendors.select2',
                    'css'      => ['select2.css'],
                    'js'       => ['select2.js'],
                    'depends'  => ['angular-ui'],
                ],
                'underscore'           => [
                    'baseUrl' => 'js/underscore',
                    'js'      => [$js('underscore')],
                ],
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

    'params'        => [
        'share' => [
            'url'         => 'http://inposted.com',
            'title'       => 'Inposted',
            'description' => 'Stay in touch with your interests.',
        ],
    ],
];