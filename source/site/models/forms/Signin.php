<?php
namespace site\models\forms;

use site\components\EmailUserIdentity as Identity;

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping user login form data.
 *
 * @property Identity $identity
 */
class Signin extends \base\FormModel
{
    public $username;
    public $password;
    public $rememberMe = true;

    /**
     * @var Identity
     */
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('username', 'required','message'=>'Введите логин/email'),
            array('password', 'required', 'on' => 'login','message'=>'Введите пароль'),
            // password needs to be authenticated
            array('password', 'authenticate', 'on' => 'login'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
            array('username', 'exist', 'className' => 'site\models\User', 'attributeName' => 'email', 'message' => 'Аккаунт не существует', 'on' => 'restore'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors() && !$this->identity->isAuthenticated) {
            $this->addError('password', 'Неверный логин или пароль.');
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     *
     * @return boolean whether login is successful
     */
    public function login() {
        if ($this->identity->isAuthenticated) {
            Yii()->user->login($this->_identity, $this->rememberMe ? 3600 * 24 * 30 : 0 /*30 days*/);
            return true;
        }
        return false;
    }

    public function getIdentity() {
        if ($this->_identity === null) {
            $this->_identity = new Identity($this->username, $this->password);
            $this->_identity->authenticate();
        }

        return $this->_identity;
    }
}