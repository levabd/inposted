<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Yurko Fedoriv
 * Date: 12/8/11
 * Time: 10:23 PM
 */
use admin\models;
use shared\components;

class AdminController extends \admin\components\Controller
{
    public $defaultAction = 'users';
    public function accessRules() {
        return \CMap::mergeArray(
            array(
                 array(
                     'allow',
                     'actions' => array('back'),
                     'expression' => function () {
                         return User()->getWasAdmin();
                     }
                 )
            ), parent::accessRules()
        );
    }

    public function actionUsers() {
        $userProvider = new \CActiveDataProvider('\admin\models\User');

        $this->render('users', compact('userProvider'));
    }

    public function actionLogin($id) {
        if ($this->login($id)) {
            User()->setWasAdmin();
            $this->goHome();
        } else {
            $this->goBack();
        }
    }

    public function actionBack() {
        $this->login(1);
        $this->goHome();
    }

    public function login($id) {
        if (User()->id != $id) {
            if ($account = models\User::model()->findByPk($id)) {
                User()->login(new components\UserIdentity($account));
                return true;
            }
        }
        return false;
    }
}
