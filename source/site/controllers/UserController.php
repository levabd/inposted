<?php
namespace site\controllers;

use site\components\RestTrait;
use site\models\User;
use site\models\Post;

class UserController extends \site\components\Controller
{
    use RestTrait;

    public $showTopMenu = false;
    public $restActions = ['validate'];

    public function filters() {
        return array(
            'accessControl - view, validate',
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

        $this->pageTitle = ['Настройки'];
        $this->attachMetaTags('user.settings');

        $this->render('settings', ['user' => $user]);
    }

    public function actionAvatarUpload() {
        $user = $this->loadModel(null);
        if ($user->validate('avatarUpload')) {
            Yii()->avatarStorage->processAvatarUpload($user);
        }
    }

    public function actionSave() {
        $model = Yii()->user->model;
        if ($model->attributes = $this->getJson()) {
            $model->save();
        }

        $this->renderModels($model);
    }

    public function actionValidate($scenario = 'insert', array $validate = []) {
        $model = new User($scenario);

        $attributes = array_intersect_key(
            $this->getJson(),
            $model->attributes + array_combine($model->safeAttributeNames, $model->safeAttributeNames)
        );

        if ($attributes) {
            foreach ($attributes as $attribute => $value) {
                try {
                    $model->$attribute = $value;
                } catch (\CException $e) {
                    //just ignore non existent attribute in case it somehow ended up in input
                }
            }
            $model->validate($validate ? : array_keys($attributes));
        }

        $this->renderJson($model->getAttributes() + $model->getAttributes($model->safeAttributeNames) + $model->getRestAttributes());
    }

    public function actionView($nickname = null) {
        $model = $this->loadModel($nickname);
        $this->author = $model;

        if($model->id == Yii()->user->id){
            $this->pageTitle = ['Моя страница'];
            $this->attachMetaTags('user.view.me');
        }
        else{
            $this->pageTitle = [$model->nickname];
            $this->attachMetaTags('user.view.other');
        }

        $this->render('//post/list');
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
