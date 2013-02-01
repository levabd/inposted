<?php
/**
 * This is the shortcut to DIRECTORY_SEPARATOR
 */
const DS = DIRECTORY_SEPARATOR;

function path($path){
    $parts = array_filter(func_get_args());
    foreach($parts as $index => $part){
        if(is_array($part)){
            $function = __FUNCTION__;
            $parts[$index] = $function($part);
        }
    }

    return str_replace('/', DS, implode(DS, $parts));
}

/**
 * Return current Unix timestamp with microseconds
 * @param mixed $variable Optional reference to variable that should be assigned with current microtime value
 * @return float
 */
function mtime(&$variable = null){
    return $variable = microtime(true);
}

/**
 * This is the shortcut to Yii::app()
 * It is type-hinted as Application class, which is unused and defined in ROOT . Application.php to place needed
 * "@property" definitions for custom application components
 * @see YiiBase::app()
 * @return Application
 */
function Yii(){
    return Yii::app();
}

/**
 * @see CWebUser
 * @return \site\components\InpostedUser
 */
function User(){
    return Yii::app()->getComponent('user');
}

/**
 * @return \shared\components\Messenger
 */
function Messenger(){
    return Yii::app()->getComponent('messenger');
}

function dump(){
    $args = func_get_args();
    $highlight = Yii() === NULL || Yii() instanceof CWebApplication;
    foreach($args as $a){
        CVarDumper::dump($a, 10, $highlight);
    }
}

function mb_ucfirst($string, $charset = null){
    if(!$charset){
        $charset = Yii()->charset;
    }

    return mb_strtoupper(mb_substr($string, 0, 1, $charset)) . mb_substr($string, 1);
}

function array_path($array, $path, $default = null, $delimiter = '.') {
    // fail if the path is empty
    if (null === $path) {
        throw new Exception('Path cannot be empty');
    }

    // remove all leading and trailing slashes
    $path = trim($path, $delimiter);

    // use current array as the initial value
    $value = $array;

    // extract parts of the path
    $parts = explode($delimiter, $path);

    // loop through each part and extract its value
    foreach ($parts as $part) {
        if ((is_array($value) ||  $value instanceof ArrayAccess) && isset($value[$part])) {
            // replace current value with the child
            $value = $value[$part];
        } else {
            // key doesn't exist, fail
            return $default;
        }
    }

    return $value;
}

function array_unflatten($array, $delimiter = '_') {
    $result = array();
    foreach ($array as $name => $value) {
        $parts = explode($delimiter, $name);
        $current = & $result;
        foreach ($parts as $part) {
            if (!isset($current[$part])) {
                $current[$part] = array();
            }
            $current = & $current[$part];
        }
        $current = is_numeric($value) ? (float)$value : $value;
    }
    return $result;
}

function array_flatten($array, $delimiter = '_', $path = null) {
    $result = array();
    foreach($array as $key => $value) {
        $nextpath = $path ? $path . $delimiter . $key : $key;
        if(is_array($value)){
            $result = array_merge($result, array_flatten($value, $delimiter, $nextpath));
        } else {
            $result[$nextpath] = $value;
        }
    }
    return $result;
}

function base64url_encode($data) {
  return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}