<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace shared\behaviors;
class MysqlDb extends \CBehavior{
    public function isDuplicateException(\CDbException $e){
        return isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062;
    }
}
