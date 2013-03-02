<?php
namespace site\controllers;

use site\models\User;
use site\models\Post;

class UserController extends \site\components\Controller
{
    public $showTopMenu = false;

    public function filters() {
        return array(
            'accessControl - view',
            '\shared\filters\ForceSuffix + index',
        );
    }

    public function accessRules() {
        return [
            ['allow', 'users' => ['@']],
            ['deny', 'users' => ['?']],
        ];
    }


    public function actions() {
        return array(
            'page' => array(
                'class'  => 'CViewAction',
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

    public function actionSettings() {
        $this->layout = 'main';
        $user = Yii()->user->model;
        $user->scenario = 'settings';
        if (($user->loadPost()) && $user->save()) {
            Yii()->avatarStorage->processAvatarUpload($user);

            Yii()->user->setFlash('settings.update', true);
            $this->refresh();
        }
        $this->render('settings', ['user' => $user]);
    }

    public function actionView($nickname = null, array $interests = [], $sort = Post::SORT_DATE) {
        $model = $this->loadModel($nickname);
        $this->author = $model;

        $criteria = new \CDbCriteria();
        if ($interests) {
            foreach ($interests as $index => $interest) {
                $criteria->addCondition("t.id IN (SELECT Post_id FROM Interest_Post WHERE Interest_id = :interest$index)");
                $criteria->params["interest$index"] = $interest;
            }
        }

        $posts = Post::model()->good()->sortBy($sort)->findAllByAttributes(['User_id' => $model->id], $criteria);

        $render = Yii()->request->isAjaxRequest ? 'renderPartial' : 'render';
        $this->$render('//post/list', ['posts' => $posts, 'sort' => $sort]);
    }

    /**
     * @param $id
     *
     * @return User
     * @throws \CHttpException
     */
    protected function loadModel($id) {
        if (is_numeric($id)) {
            $user = User::model()->findByPk($id);
        } elseif ($id) {
            $user = User::model()->findByAttributes(['nickname' => $id]);
        } else {
            $user = Yii()->user->model;
        }

        if (!$user) {
            throw new \CHttpException(404);
        }

        return $user;
    }
}
