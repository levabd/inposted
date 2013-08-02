<?php
namespace site\models\forms;

class Contact extends \base\FormModel
{
    public $name;
    public $email;
    public $subject;
    public $url;
    public $body;
    public $verifyCode;
    public $skipVerify = false;

    public function rules() {
        return array(
            // name, email, subject and body are required
            array('name, email, subject, body', 'required'),
            // email has to be a valid email address
            array('email', 'email'),
            array('url', 'url'),
            array('skipVerify', 'safe'),
            // verifyCode needs to be entered correctly
            array('verifyCode', 'captcha', 'allowEmpty' => !\CCaptcha::checkRequirements() || Yii()->getRequest()->getPost('skipVerify')),
        );
    }

    public function attributeLabels() {
        return array(
            'url' => 'Ваш веб-сайт',
            'verifyCode' => 'Код подтверждения',
        );
    }

    public static function getAverageReplyTime() {
        if(date('N') > 5){ // weekend
            return 24*(8-date('N')) - date('H') + 9; // we start at 9am on Monday
        } else if(date('H')>=16) {
            if(date('N') == 5){ //friday evening
                return 24 + 24 + 24 - date('H') + 9;
            }
            return 24 - date('H') + 9;
        } else if(date('H') < 5) { // e.g. it is more than 4 hours till 9am
            return 9 - date('H');
        }
        return 4; // 4 hours by default
    }
}