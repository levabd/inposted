<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Yurko Fedoriv
 * Date: 12/8/11
 * Time: 10:25 PM
 */

namespace shared\components;
class Controller extends \base\Controller
{
    public function init() {
        parent::init();

        if(!Yii()->user->isGuest){
            if(!Yii()->user->model){
                Yii()->user->logout();
                $this->redirect(['site/index']);
            }
            Yii()->user->model->lastOnline = new \CDbExpression('NOW()');
            Yii()->user->model->update(['lastOnline']);
        }
    }


    public function goBack($default = null) {
        if (Yii()->getRequest()->getIsAjaxRequest()) {
            return;
        }

        $default = $default ? : array('index');

        $this->redirect(Yii()->getRequest()->getUrlReferrer() ? : $default);
    }

    protected function goHome() {
        $this->redirect(User()->getHomeUrl());
    }
}
