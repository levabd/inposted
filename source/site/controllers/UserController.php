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

    public function actionView($nickname, array $interests = []){
        $model = $this->loadModel($nickname);
        $this->author = $model;

        $criteria = new \CDbCriteria(['scopes' => ['good', 'byDate']]);
        if($interests){
            foreach($interests as $index => $interest){
                $criteria->addCondition("posts.id IN (SELECT Post_id FROM Interest_Post WHERE Interest_id = :interest$index)");
                $criteria->params["interest$index"] = $interest;
            }
        }

        $posts = $model->posts($criteria);

        $render = Yii()->request->isAjaxRequest ? 'renderPartial' : 'render';
        $this->$render('//post/list', ['posts' => $posts]);
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
