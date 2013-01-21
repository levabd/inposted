<?php
namespace site\controllers;

use site\models;
use site\components;
use site\components\InpostedUser;

class AuthController extends components\Controller
{
    /**
     * @var InpostedUser
     */
    protected $user;

    public function filters() {
        return array(
            'accessControl + signout, verify',
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

//
//    public function actions() {
//        return array(
//            'captcha' => array(
//                'class' => 'CCaptchaAction',
//                'backColor' => 0xFFFFFF,
//                'foreColor' => 0x8DBB10,
//                'testLimit' => 1,
//                ),
//        );
//    }

    public function behaviors() {
        return array(
            array('class' => 'shared\behaviors\SignedUrlBehavior')
        );
    }

    public function init() {
        parent::init();

        $this->user = Yii()->user;
    }

    public function actionIndex() {
        $this->render('index');
    }

    protected function goSignIn() {
        $this->redirect(array('signin'));
    }

    protected function goSignUp() {
        $this->redirect(array('signup'));
    }

    public function actionSignup($type = 'account') {
        $action = 'signup';

//        if ($this->user->isGuest) {
        $action .= 'Guest';
//        } else {
//            $action .= 'User';
//        }

        if (in_array($type, array('account'))) {
            $action .= ucfirst($type);
        }

        if (method_exists($this, $action)) {
            return $this->$action();
        } else {
            $this->goHome();
        }
    }

    protected function signupGuestAccount() {
        $model = new models\User('signup');
        if ($data = $model->getPost()) {
            $model->attributes = $data;
            $transaction = $model->getDbConnection()->beginTransaction();

            if ($model->validate()) {
                try {
                    $model->saveOrThrow(false);

                    $profile = new models\Profile;
                    $profile->accountId = $model->id;
                    $profile->saveOrThrow(false);

                    $model->createDemoSpins();

                    $transaction->commit();
                    $this->signUp($model);

                } catch (\Exception $e) {
                    $transaction->rollback();

                    throw $e;
                }
            }
        }

        $model->password = $model->passwordRepeat = null;

        $this->render('signup-guest-account', array('model' => $model));
    }

    public function actionVerify($policy = null) {
        if (!$policy) {
            $account = User()->getAccount();
            $this->sendVerifictionLink($account);
            $this->goBack();

        } else {
            $policy = $this->decryptPolicy($policy);
            if (!$policy) {
                throw new CHttpException(403);
            }

            list($route, $params, $time) = $policy;

            $email = array_path($params, 'email');

            if (time() - $time > 1800) {
                User()->setError('Unable to verify email. Signature expired');
            } else
            if ($email != User()->getAccount()->email) {
                User()->setError('Unable to verify email. Emails don\'t match.');
            } else {
                User()->getAccount()->setVerified();
                User()->setSuccess('Your email was successfully verified.');
            }
            $this->goHome();
        }
    }

    public function actionSignin() {
        if (!$this->user->isGuest) {
            $this->goHome();
        }

        $model = new Signin('login');
        if ($data = Yii()->getRequest()->getPost(get_class($model))) {
            // collects user input data
            $model->attributes = $data;
            // validates user input and redirect to previous page if validated
            $this->signIn($model);
        }

        Yii()->clientScript->registerPackage('angular');
        // displays the login form
        $this->render('signin', array('model' => $model));
    }

    public function actionRestore($policy = null) {
        if (!$this->user->isGuest) {
            $this->goHome();
        }

        if(!$policy){
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

            list($route, $params, $time) = $policy;

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

    protected function signUp(models\User $account) {
        if ($account->validate()) {
            InpostedUser::makeUser($account->id);

            $this->sendVerifictionLink($account);

            User()->login(new shared\components\UserIdentity($account));
            $this->goHome();
        }
    }

    protected function signIn(Signin $form) {
        if ($form->validate() && $form->login()) {
            //            $this->user->logout();
            $returnUrl = $this->user->getReturnUrl() ? : $this->user->getHomeUrl();
            $this->redirect($returnUrl);
        }
    }

    protected function restoreRequest(Restore $form) {
        sleep(1);
        /** @var $model Account */
        if ($form->validate()) {
            $this->sendRestorePasswordLink($form);
            $this->goSignIn();
        } else {
            sleep(3);
        }
    }

    protected function restoreSetPassword(Restore $form, $email) {
        /** @var $model Account */
        if ($form->validate()) {
            /** @var $account \site\models\User */
            $account = \site\models\User::model()->findByEmail($email);
            $account->resetPassword($form->password);
            $account->save();

            User()->setSuccess('Your password was successfully changed');
            User()->login(new shared\components\UserIdentity($account));
            $this->goHome();
        }
    }

    public function actionSignout() {
        $this->user->logout();

        $this->goSignIn();
    }

    protected function sendVerifictionLink(models\User $account) {
        $verificationLink = $this->createSignedUrl('/auth/verify', array('email' => $account->email));
        Messenger()->send(
            'email-verification',
            $account->email,
            array(
                 'firstName' => $account->firstName,
                 'link' => $verificationLink
            )
        );
        User()->setSuccess("Verification link was sent to {$account->email}.");
    }

    protected function sendRestorePasswordLink(Restore $form) {
        $restorePasswordLink = $this->createSignedUrl('/auth/restore', array('username' => $form->username));

        Messenger()->send(
            'password-reset',
            $form->username,
            array(
                 'firstName' => $form->account->firstName,
                 'link' => $restorePasswordLink
            )
        );
        User()->setSuccess("Password restore link was sent to <strong>{$form->username}</strong>");
    }
}