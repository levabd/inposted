<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yurko
 * Date: 12/8/11
 * Time: 10:22 PM
 * To change this template use File | Settings | File Templates.
 */
namespace admin\components;
class Controller extends \shared\components\Controller
{
    public function filters() {
        return array('accessControl');
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'roles' => array('Admin'),
            ),
            array('deny'),
        );
    }
}
