<?php
namespace site\controllers;

use site\models;
use site\components;
use site\components\InpostedUser;
use CHttpException;
use CUploadedFile;
use site\models\User;
use site\models\forms\Signin;
use site\models\forms\Restore;

/**
 * @method string createSignedUrl($route, $policyParams = array(), $schema = '')
 * @method array  decryptPolicy($message)
 * @method void   fsDir($target);
 */
class AuthController extends components\WidgetController
{
    use components\RestTrait;

    public $defaultAction = 'signin';

    /**
     * @var InpostedUser
     */
    protected $user;

    public function filters() {
        return array(
            'accessControl + verify',
            'guest + signin, signup',
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'roles' => array('User')
            ),
            array(
                'deny',
            ),
        );
    }

    public function filterGuest(\CFilterChain $filterChain) {
        !$this->user->isGuest && $this->goHome();
        $filterChain->run();
    }

    public function behaviors() {
        return array(
            array('class' => 'shared\behaviors\SignedUrlBehavior'),
            array('class' => 'shared\behaviors\FsBehavior'),
        );
    }

    public function init() {
        parent::init();

        $this->user = Yii()->user;
    }

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            'oauth' => array(
                // the list of additional properties of this action is below
                'class'             => 'site\components\oauth\OAuthAction',
                // Yii alias for your user's model, or simply class name, when it already on yii's import path
                // default value of this property is: User
                'model'             => 'site\models\User',
                'identityClass'     => 'shared\components\UserIdentity',
                'formView'          => 'oauth-form',
                'usernameAttribute' => 'nickname',
                // map model attributes to attributes of user's social profile
                // model attribute => profile attribute
                // the list of available attributes is below
                'attributes'        => array(
                    'email'        => 'email',
                    'name'         => function ($profile) {
                        return $profile->firstName . ' ' . $profile->lastName;
                    },
                    'country'      => 'country',
                    'info'         => 'description',
                    'nickname'     => 'displayName',
                    'homepage'     => function ($profile) {
                        return $profile->webSiteURL ? : $profile->profileURL;
                    },
                    'avatarSource' => function ($profile) {
                        //tweak for photo url from twitter
                        if (strpos($profile->photoURL, 'twimg.com')) {
                            return str_replace('_normal', '', $profile->photoURL);
                        }
                        return $profile->photoURL;
                    },
                    'birthYear'    => 'birthYear',
                    'gender'       => 'gender',
                    // you can also specify additional values,
                    // that will be applied to your model (eg. account activation status)
                    'verified'     => function ($profile) {
                        return (bool) $profile->emailVerified;
                    },
                ),
            ),
            // this is an admin action that will help you to configure HybridAuth
            // (you must delete this action, when you'll be ready with configuration, or
            // specify rules for admin role. User shouldn't have access to this action!)
//            'oauthadmin' => array(
//                'class' => 'ext.hoauth.HOAuthAdminAction',
//            ),
        );
    }

    protected function goSignIn() {
        $this->redirect(array('signin'));
    }

    protected function goSignUp() {
        $this->redirect(array('signup'));
    }

    public function actionSignup() {
        if (!($data = $this->getJson())) {
            $this->renderPartial('signup');
            return;
        }

        $model = new models\User('signup');

        if (($model->attributes = $this->getJson()) && $model->save()) {
            InpostedUser::makeUser($model->id);
            $this->sendVerificationLink($model);
            User()->login(new \shared\components\UserIdentity($model));
        }

        $this->renderModels($model);
    }

    /**
     * @param string $policy
     *
     * @throws \CHttpException
     */
    public function actionVerify($policy = null) {
        $user = User();
        $userModel = $user->getModel();
        if (!$policy) {
            $this->sendVerificationLink($userModel);
            $this->goBack();

        } else {
            $policy = $this->decryptPolicy($policy);
            if (!$policy) {
                throw new CHttpException(403);
            }

            list(, $params, $time) = $policy;

            $email = array_path($params, 'email');

            if (time() - $time > 1800) {
                $user->setError('Не удалось подтвердить e-mail. Истек ключ.');
            } else {
                if ($email != $userModel->email) {
                    $user->setError('Не удалось подтвердить e-mail. Элетронные адреса не совпадают.');
                } else {
                    $userModel->markVerified();
                    $user->setSuccess('Ваш элетронный адрес был успешно подтвержден.');
                }
            }
            $this->goHome();
        }
    }

    public function actionSignin() {
//        $this->layout = '//auth/layout';
        $model = new models\forms\Signin('login');
        if ($model->attributes = $this->getJson()) {
            // validates user input and redirect to previous page if validated
            if ($model->validate() && $model->login()) {
                $this->renderJson(['success' => true]);
            } else {
                $this->renderJson(
                    [
                    'errors' => [
                        'username' => $model->getError('username'),
                        'password' => $model->getError('password'),
                    ]
                    ]
                );
            }
        } else {
            // displays the login form
            $this->renderPartial('signin', compact('model'));
        }
    }

    /**
     * @param string $policy
     *
     * @throws \CHttpException
     */
    public function actionRestore($policy = null) {

        if (!$this->user->isGuest) {
            $this->goHome();
        }

        if (!$policy) {
            $this->layout = '//auth/layout';
            $model = new Restore('request');
            if ($model->attributes = $this->getJson()) {
                /** @var $model User */
                if ($model->validate()) {
                    $this->sendRestorePasswordLink($model);
                } else {
                    sleep(3);
                    $this->renderJson(
                        [
                        'errors' => [
                            'username' => $model->getError('username'),
                            'password' => $model->getError('password'),
                        ]
                        ]
                    );
                }
            }
        } else {
            $policy = $this->decryptPolicy($policy);
            if (!$policy) {
                throw new CHttpException(403, 'Неверный ключ');
            }

            list(, $params, $time) = $policy;

            $username = array_path($params, 'username');

            if (time() - $time > 900) {
                throw new CHttpException(403, 'Ключ истек');
            }

            $model = new Restore('set-password');
            $model->username = $username;
            if ($model->loadPost()) {
                $this->restoreSetPassword($model, $username);
            }
            $this->render('restore-set-password', array('model' => $model));
        }
    }

    protected function restoreSetPassword(Restore $form, $email) {
        if ($form->validate()) {
            /** @var $user \site\models\User */
            $user = \site\models\User::model()->findByEmail($email);
            $user->resetPassword($form->password);
            $user->save();

            User()->setSuccess('Ваш пароль был успешно изменен.');
            User()->login(new \shared\components\UserIdentity($user));
            $this->goHome();
        }
    }

    public function actionSignout() {
        $this->user->logout();

        $this->goHome();
    }

    public function actionSendVerificationLink() {
        if ($model = Yii()->user->model) {
            $this->sendVerificationLink($model);
            $this->renderJson(true);
        } else {
            $this->renderJson(false);
        }
    }

    public function sendVerificationLink(\shared\models\User $user) {
        $verificationLink = $this->createSignedUrl('site:/auth/verify', array('email' => $user->email));
        Messenger()->send(
            'email-verification',
            $user->email,
            array(
                 'firstName' => $user->firstName,
                 'link'      => $verificationLink
            )
        );
    }

    protected function sendRestorePasswordLink(Restore $form) {
        $restorePasswordLink = $this->createSignedUrl('site:/auth/restore', array('username' => $form->username));

        Messenger()->send(
            'password-reset',
            $form->user->email,
            array(
                 'firstName' => $form->user->firstName,
                 'link'      => $restorePasswordLink
            )
        );

        $this->renderJson(['success' => "Ссылка на восстановление пароля была отправлена {$form->username}"]);
    }
}