<?php
namespace site\controllers;

use site\models\User;

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

    public function actionView($nickname){
        $model = $this->loadModel($nickname);
        $this->author = $model;

        $posts = $model->posts(['scopes' => ['good', 'byDate']]);

        $this->render('view', ['posts' => $posts]);
    }

    /**
     * @param $id
     *
     * @return User
     * @throws \CHttpException
     */
    protected function loadModel($id) {
        if(is_numeric($id)){
            $user = User::model()->findByPk($id);
        }
        else{
            $user = User::model()->findByAttributes(['nickname' => $id]);
        }

        if(!$user){
            throw new \CHttpException(404);
        }

        return $user;
    }
}
