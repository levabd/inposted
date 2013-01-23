<?php
namespace site\controllers;
use Yii, CHtml, Exception;
class SiteController extends \site\components\Controller
{
    public function actions() {
        return array(
            'page' => array(
                'class' => 'CViewAction',
            ),
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'foreColor' => 0x8DBB10,
                'testLimit' => 1,
            ),
        );
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionContact() {
        $model = new \Contact;
        $successFlashName = 'contact-success';
        $success = false;

        if ($data = Yii()->getRequest()->getPost(get_class($model))) {
            $model->attributes = $data;
            if($model->validate()){
                $from = $model->email;
                $subject = CHtml::encode($model->subject);
                $body = CHtml::encode($model->body);
                $name = CHtml::encode($model->name);
                $url = CHtml::encode($model->url);

                if($url){
                    $body .= "\n\n--\n$url";
                }

                $msg = Messenger()->getMailer()->create($subject, $body)
                    ->setReplyTo($from, $name)
                    ->setTo(Messenger()->getAddress('support'));

                try{
                    Messenger()->getMailer()->send($msg);
                    User()->setSuccess("Thank you for contacting us");

                    User()->setFlash($successFlashName,\CJSON::encode($model));
                    $this->goBack();
                } catch(Exception $e){
                    User()->setError("Message was not sent. We were notified about this error.");
                    $logmsg = "Couldn't send contact message:\n"
                        ."From    = $name <$from>\n"
                        ."Subject = $subject\n"
                        ."Body    = $body\n"
                        ."\nReceived error: {$e->getMessage()}";
                    Yii::log($logmsg, \CLogger::LEVEL_ERROR, 'email');
                }
            }
        } else
        if($data = User()->getFlash($successFlashName)){
            $success = true;
            $model->setAttributes(\CJSON::decode($data));
        }

        $this->render('contact', compact('success','model'));
    }

    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            $this->render('error', $error);
        }
    }
}