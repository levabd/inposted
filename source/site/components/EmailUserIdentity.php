<?php
namespace site\components;

use site\models\User;

class EmailUserIdentity extends \CUserIdentity
{
    private $_id;
    public $password;

    const ERROR_ACCOUNT_NOT_VERIFIED = 3;

    public function  __construct($email, $password) {
        $this->username = $email;
        $this->password = $password;
    }

    public function authenticate() {
        /** @var $account  \site\models\User*/
        if ($account = User::model()->findByAttributes(array('email' => $this->username))) {
//            if(!$user->verified){
//                $this->errorCode = self::ERROR_ACCOUNT_NOT_VERIFIED;
//            } else
            if (!$account->validatePassword($this->password)) {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            } else {
                $this->_id = $account->id;
                $this->errorCode = self::ERROR_NONE;
            }
        } else {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        return !$this->errorCode;

    }

    public function getId() {
        return $this->_id;
    }
}