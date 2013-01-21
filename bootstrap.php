<?php
/** @author: Yura Fedoriv <yuri@luckyteam.co.uk> */
/*===Project is compatible with PHP 5.3+ only===*/
define('ROOT', __DIR__);

//<editor-fold defaultstate="collapsed" desc="Handler for fatal errors">
ini_set('display_errors', false);
ini_set('log_errors', false);
register_shutdown_function(
    function () {
        $error = error_get_last();
        if (null !== $error && ($error['type'] & error_reporting())) {
            if (class_exists('Yii', false) && class_exists('CApplication', false) && (Yii::app() instanceof CApplication)) {
                Yii::app()->handleError($error['type'], $error['message'], $error['file'], $error['line']);
            } else {
                !headers_sent() && header("HTTP/1.0 500 Internal Server Error");
                switch ($error['type']) {
                    case E_ERROR:
                        $type = 'PHP Fatal error';
                        break;
                    case E_PARSE:
                        $type = 'Parse error';
                        break;
                    default:
                        $type = 'PHP error';
                }
                $message = "$type: {$error['message']} in {$error['file']} on line {$error['line']}";
                error_log($message);
                echo $message;
            }
        }
    }
);
//</editor-fold>

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
