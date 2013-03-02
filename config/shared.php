<?php
/** @var $name string Name of sub-application */
Yii::setPathOfAlias('base', ROOT . '/source/shared/extensions/base');

/** @var string $keyPrefix Needed for sharing components data between sub-applications (session, cache) */
$keyPrefix = '412d9d2ff7f70f893dd67cfa940c8275';
$cookieDomain = '*.inposted.com';

return [
    'basePath'    => path(ROOT, 'source', $name),
    'runtimePath' => path(ROOT, 'runtime', $name),
    'preload'     => ['log'],

    'import'      => ['shared.extensions.base.CHtml'],

    'components'  => [
        'messenger'      => [
            'class'     => 'shared\components\Messenger',
            'mailerId'  => 'mailer',
            'addresses' => [],
        ],
        'mailer'         => [
            'class'     => 'shared\extensions\mail\Mailer',
            'transport' => [
                'type'       => 'smtp',
                'server'     => 'smtp.gmail.com',
                'port'       => 465,
                'encryption' => 'ssl',
                'username'   => 'info@inposted.com',
                'password'   => 'keboho704~',
            ],
            'message'   => [
                'from' => ['info@inposted.com' => 'Inposted'],
            ]
        ],

        'crypt'          => [
            'class' => 'shared\components\Crypt',
            'key'   => 'fb07e89e71e7cfb04285d30144fc5bda',
        ],

        'session'        => [
            'class'        => 'CCacheHttpSession',
            'cookieParams' => ['domain' => $cookieDomain],
            'timeout'      => 3600 * 24 * 7,
        ],

        'user'           => [
            'class'           => 'site\components\InpostedUser',
            'loginUrl'        => ['site:site/index'],
            // enable cookie-based authentication
            'allowAutoLogin'  => true,
            'autoRenewCookie' => true,
            'stateKeyPrefix'  => $keyPrefix,
            'identityCookie'  => ['domain' => $cookieDomain]
        ],

        'statePersister' => [
            'class' => 'base\CacheStatePersister',
        ],

        'authManager'    => [
            'class'        => 'CDbAuthManager',
            'connectionID' => 'db',
        ],

        'cache'          => [
            'class'        => 'CMemCache',
            'useMemcached' => true,
            'servers'      => [
                'one' => [
                    'host' => 'localhost',
                ]
            ],
            'keyPrefix'    => $keyPrefix,
        ],

        'request'        => [
            'class' => 'base\HttpRequest',
        ],

        'urlManager'     => [
            'class'    => 'base\url\ManagerCollection',

            'managers' => [
                'site'  => [
                    'host'           => "http://inposted.com",
                    'urlFormat'      => 'path',
                    'urlSuffix'      => '/',
                    'showScriptName' => false,
                    'rules'          => [
                        ''                         => 'site/index',
                        'register/step<step>'      => 'auth/signup',
                        'register'                 => 'auth/signup',
                        'register/verify/<policy>' => 'auth/verify',
                        'register/verify'          => 'auth/verify',

                        'settings'                 => 'user/settings',
                        'signout'                  => 'auth/signout',

                        'profile'                  => ['user/view', 'defaultParams' => ['nickname' => false]],
                        'profile/<nickname>'       => 'user/view',
                        '<id:\d+>'                 => 'post/view',
                        'vote/<id:\d+>/<type>'     => 'post/vote',
                        'go/<eid:\w+>'             => ['go/go', 'urlSuffix' => false],
                    ]
                ],
                'admin' => [
                    'host'           => "https://admin.inposted.com",
                    'urlFormat'      => 'path',
                    'showScriptName' => false,
                ],
            ]
        ],

        'db'             => [
            'connectionString'   => sprintf('mysql:host=%s;dbname=%s', 'localhost', 'inposted'),
            'username'           => 'inposted.com',
            'password'           => 'inposted',
            'charset'            => 'utf8',
            'enableParamLogging' => YII_DEBUG,
        ],

        'format'         => [
            'class' => 'shared\components\Formatter'
        ],

        'log'            => [
            'class'  => 'CLogRouter',
            'routes' => [
                'email'       => [
                    'class'      => 'CFileLogRoute',
                    'categories' => 'email',
                    'logFile'    => 'email.log',
                ],
                'application' => [
                    'class'  => 'CFileLogRoute',
                    'levels' => 'error, warning, info',
                ],
                'error'       => [
                    'class'   => 'CFileLogRoute',
                    'levels'  => 'error, warning',
                    'logFile' => 'error.log',
                ],
                'info'        => [
                    'class'   => 'CFileLogRoute',
                    'levels'  => 'info',
                    'logFile' => 'info.log',
                ],
                'trace'       => [
                    'class'   => 'CFileLogRoute',
                    'levels'  => 'trace',
                    'logFile' => 'trace.log',
                ],
            ],
        ],

        'avatarStorage'  => [
            'class'    => 'shared\components\AvatarStorage',
            'appId'    => 'site',
            'basePath' => ROOT . '/web/site/avatars',
            'baseUrl'  => 'avatars',
            'sizes'    => [56, 73, 210],
        ],

        'fs'             => [
            'class'         => 'shared\components\Fs',
            'safeLocations' => [ROOT . '/web/site/avatars'],
        ]
    ],

    'params'      => [
        'safeLocations' => [ROOT . '/web/site/avatars']
    ]
];
