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
class AuthController extends components\Controller
{
    public $defaultAction = 'signin';

    /**
     * @var InpostedUser
     */
    protected $user;

    public function filters() {
        return array(
            'accessControl + signout, verify',
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

    protected function goSignIn() {
        $this->redirect(array('signin'));
    }

    protected function goSignUp() {
        $this->redirect(array('signup'));
    }

    public function actionSignup($step = null) {
        !$step && $this->redirect(['', 'step' => 1]);
        if ($step > 2) {
            throw new CHttpException(404);
        }

        $scenario = "signup-$step";

        $model = null;
        if ($currentId = User()->getState('signup.user.id')) {
            if ($model = models\User::model()->findByPk($currentId)) {
                $model->scenario = $scenario;
            } else {
                User()->setState('signup.user.id', null);
            }
        }

        if (!$model) {
            if (1 == $step) {
                $model = new models\User($scenario);
            } else {
                $this->redirect(['', 'step' => 1]);
            }
        }

        if ($model->attributes = $model->getPost()) {
            $model->avatarUpload = CUploadedFile::getInstance($model, 'avatarUpload');
            if ($model->save()) {
                if (1 == $step) {
                    User()->setState('signup.user.id', $model->id);
                    $this->redirect(['', 'step' => 2]);
                } else {
                    if ($model->avatarUpload) {
                        $model->generateAvatarName($model->avatarUpload->extensionName);
                        $file = $model->getAvatarFile();
                        $this->fsDir(dirname($file));
                        $model->avatarUpload->saveAs($file);
                    }
                    InpostedUser::makeUser($model->id);

                    $this->sendVerificationLink($model);

                    User()->login(new \shared\components\UserIdentity($model));
                    User()->setState('signup.user.id', null);
                    $this->goHome();
                }
            }

        }
        $model->password = null;

        $this->render("signup-$step", compact('model'));
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
                $user->setError('Unable to verify email. Signature expired');
            } else {
                if ($email != $userModel->email) {
                    $user->setError('Unable to verify email. Emails don\'t match.');
                } else {
                    $userModel->markVerified();
                    $user->setSuccess('Your email was successfully verified.');
                }
            }
            $this->goHome();
        }
    }

    public function actionSignin() {
        $model = new models\forms\Signin('login');
        if ($model->attributes = Yii()->getRequest()->getPost(get_class($model))) {
            // validates user input and redirect to previous page if validated
            $this->signIn($model);
        }

        // displays the login form
        $this->render('signin', array('model' => $model));
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
            $model = new Restore('request');
            $model->username = Yii()->getRequest()->getQuery('user');
            if ($data = Yii()->getRequest()->getPost(get_class($model))) {
                $model->attributes = $data;
                // validates user input and redirect to previous page if validated
                $this->restoreRequest($model);
            }
            $this->render('restore-request', array('model' => $model));
        } else {
            $policy = $this->decryptPolicy($policy);
            if (!$policy) {
                throw new CHttpException(403, 'Invalid signature');
            }

            list(, $params, $time) = $policy;

            $username = array_path($params, 'username');

            if (time() - $time > 900) {
                throw new CHttpException(403, 'Signature expired');
            }

            $model = new Restore('set-password');
            $model->username = $username;
            if ($data = Yii()->getRequest()->getPost(get_class($model))) {
                $model->attributes = $data;
                // validates user input and redirect to previous page if validated
                $this->restoreSetPassword($model, $username);
            }
            $this->render('restore-set-password', array('model' => $model));
        }
    }

    protected function signIn(Signin $form) {
        if ($form->validate() && $form->login()) {
            $returnUrl = $this->user->getReturnUrl() ? : $this->user->getHomeUrl();
            $this->redirect($returnUrl);
        }
    }

    protected function restoreRequest(Restore $form) {
        sleep(1);
        /** @var $model User */
        if ($form->validate()) {
            $this->sendRestorePasswordLink($form);
            $this->goSignIn();
        } else {
            sleep(3);
        }
    }

    protected function restoreSetPassword(Restore $form, $email) {
        if ($form->validate()) {
            /** @var $user \site\models\User */
            $user = \site\models\User::model()->findByEmail($email);
            $user->resetPassword($form->password);
            $user->save();

            User()->setSuccess('Your password was successfully changed');
            User()->login(new \shared\components\UserIdentity($user));
            $this->goHome();
        }
    }

    public function actionSignout() {
        $this->user->logout();

        $this->goSignIn();
    }

    protected function sendVerificationLink(\shared\models\User $user) {
        $verificationLink = $this->createSignedUrl('site:/auth/verify', array('email' => $user->email));
        Messenger()->send(
            'email-verification',
            $user->email,
            array(
                 'firstName' => $user->firstName,
                 'link'      => $verificationLink
            )
        );
        User()->setSuccess("Verification link was sent to {$user->email}.");
    }

    protected function sendRestorePasswordLink(Restore $form) {
        $restorePasswordLink = $this->createSignedUrl('site:/auth/restore', array('username' => $form->username));

        Messenger()->send(
            'password-reset',
            $form->username,
            array(
                 'firstName' => $form->user->firstName,
                 'link'      => $restorePasswordLink
            )
        );
        User()->setSuccess("Password restore link was sent to <strong>{$form->username}</strong>");
    }
}