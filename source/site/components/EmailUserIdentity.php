<?php
namespace site\components;

use site\models\User;

class EmailUserIdentity extends \CUserIdentity
{
    private $_id;
    public $password;

    public function  __construct($email, $password) {
        $this->username = $email;
        $this->password = $password;
    }

    public function authenticate() {
        /** @var $user  \site\models\User*/
        if ($user = User::model()->findByAttributes(array('email' => $this->username))) {
            if (!$user->validatePassword($this->password)) {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            } else {
                $this->_id = $user->id;
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