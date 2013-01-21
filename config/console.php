<?php
/** @var $name string Name of sub-application */
Yii::getLogger()->autoFlush = 1;
Yii::getLogger()->autoDump = true;

return [
    'name' => 'Inposted::CLI',

    'commandMap' => [
        'migrate' => [
            'class' => 'system.cli.commands.MigrateCommand',
            'migrationPath' => 'root.migrations',
            'migrationTable' => 'YiiMigration',
        ],
    ],
    // application components
    'components' => [
        'mime' => [
            'class' => '\console\components\Mime',
        ],
    ],
];
