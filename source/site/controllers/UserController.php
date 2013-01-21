<?php
namespace site\controllers;

use \site\models;

class UserController extends \site\components\Controller
{
    public $showTopMenu = false;

    public function filters() {
        return array(
            'accessControl - preview',
            array(
                '\shared\filters\ForceSuffix + index',
            ),
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'roles' => array('User'),
            ),
            array(
                'deny',
                'users' => array('*'),
            ),
        );
    }

    public function actions() {
        return array(
            'page' => array(
                'class' => 'CViewAction',
                'layout' => false,
            )
        );
    }

    public function behaviors() {
        return array(
            array('class' => 'shared\behaviors\SignedUrlBehavior')
        );
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionAccount() {
        $this->render('account');
    }
}
