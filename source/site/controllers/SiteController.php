<?php
namespace site\controllers;
use Yii, CHtml, Exception;
use site\components\RestTrait;
use site\models\Post;

class SiteController extends \site\components\Controller
{
    use RestTrait;

    public $restActions = ['share'];

    public function actions() {
        return array(
            'page'    => array(
                'class' => 'CViewAction',
            ),
            'captcha' => array(
                'class'     => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'foreColor' => 0x8DBB10,
                'testLimit' => 1,
            ),
        );
    }

    public function actionIndex() {
        $this->pageTitle = Yii()->user->isGuest ? [] : ['Домашняя страница'];
        $this->attachMetaTags(Yii()->user->isGuest ? 'site.index.guest' : 'site.index.user');

        $this->render('//post/list');
    }

    public function actionContact() {
        $model = new \Contact;
        $successFlashName = 'contact-success';
        $success = false;

        if ($data = Yii()->getRequest()->getPost(get_class($model))) {
            $model->attributes = $data;
            if ($model->validate()) {
                $from = $model->email;
                $subject = CHtml::encode($model->subject);
                $body = CHtml::encode($model->body);
                $name = CHtml::encode($model->name);
                $url = CHtml::encode($model->url);

                if ($url) {
                    $body .= "\n\n--\n$url";
                }

                $msg = Messenger()->getMailer()->create($subject, $body)
                    ->setReplyTo($from, $name)
                    ->setTo(Messenger()->getAddress('support'));

                try {
                    Messenger()->getMailer()->send($msg);
                    User()->setSuccess("Спасибо за то что связались с нами");

                    User()->setFlash($successFlashName, \CJSON::encode($model));
                    $this->goBack();
                } catch (Exception $e) {
                    User()->setError("Сообщение не было отослано. Мы уведомлены об этой ошибке.");
                    $logmsg = "Невозможно отправить сообщение:\n"
                        . "From    = $name <$from>\n"
                        . "Subject = $subject\n"
                        . "Body    = $body\n"
                        . "\nReceived error: {$e->getMessage()}";
                    Yii::log($logmsg, \CLogger::LEVEL_ERROR, 'email');
                }
            }
        } else {
            if ($data = User()->getFlash($successFlashName)) {
                $success = true;
                $model->setAttributes(\CJSON::decode($data));
            }
        }

        $this->render('contact', compact('success', 'model'));
    }

    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii()->request->isAjaxRequest) {
                header('Content-Type: application/json');
                echo \CJSON::encode($error);
            } else {
                $this->render('error', $error);
            }
        }
    }

    public function actionShare($emails = null, $message = null) {
        $link = 'http://inposted.com';
        if ($emails) {
            $mailer = Yii()->mailer;

            $title = 'Приглашение посетить Inposted.com';
            $body = ($message ? : 'Перейдите по этой ссылке:') . "\n\n" . $link;

            $errors = (object)[];
            foreach (array_unique(preg_split('/[,\s]+/', $emails)) as $email) {
                try {
                    $message = $mailer->create($title, $body, 'text/plain', Yii()->charset)->setTo($email);
                    if (!$mailer->send($message, $failures)) {
                        $errors->$email = 'Не удалось отправить сообщение';
                    }
                } catch (Exception $e) {
                    $errors->$email = $e->getMessage();
                }
            }
            $this->renderJson($errors);
        } else {
            $this->pageTitle = ['Поделиться'];
            $this->attachMetaTags('site.share');
            $this->render('share', compact('link'));
        }
    }
}
