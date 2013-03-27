<?php
/** @author: Yura Fedoriv <yuri@luckyteam.co.uk> */
/*===Project is compatible with PHP 5.3+ only===*/
define('ROOT', __DIR__);

is_file($debug = ROOT . '/debug.php') && require_once $debug;

require_once ROOT . '/yii/framework/yii.php';
require_once ROOT . '/source/shared/shortcuts.php';

Yii::setPathOfAlias('root', ROOT);
foreach (array_diff(scandir(ROOT . '/source'), array('.', '..')) as $sourceRoot) {
    Yii::setPathOfAlias($sourceRoot, ROOT . "/source/$sourceRoot/");
}

return function ($type, $name = null) {
    !$name && ($name = strtolower($type));
    $class = in_array($type, ['web', 'console']) ? 'C' . ucfirst($type) . 'Application' : $type;

    is_file($shortcuts = ROOT . "/source/$name/shortcuts.php") && require_once $shortcuts;

    $config = array('id' => $name);
    if(is_a($class, 'CWebApplication', true)){
        $config['controllerNamespace'] = "$name\\controllers";
    }

    //closure to create separate scope for config file
    $configLoader = function () use ($name) {
        return require func_get_arg(0);
    };

    $configFiles = [
        ROOT . '/config/shared.php',
        ROOT . '/config/shared.local.php',
        ROOT . "/config/$name.php",
        ROOT . "/config/$name.local.php",
    ];

    foreach ($configFiles as $configFile) {
        if (is_file($configFile)) {
            $config = CMap::mergeArray($config, $configLoader($configFile));
        }
    }

    Yii::createApplication($class, $config)->run();
};
