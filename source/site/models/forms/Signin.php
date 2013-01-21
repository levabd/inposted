<?php
//namespace site\models\forms;

use \site\components\EmailUserIdentity as Identity;

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping user login form data.
 *
 * @property Identity $identity
 */
class Signin extends CFormModel
{
    public $username;
    public $password;
    public $rememberMe;

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
            array('username', 'required'),
            array('username', 'email'),
            array('password', 'required', 'on' => 'login'),
            // password needs to be authenticated
            array('password', 'authenticate', 'on' => 'login'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
            array('username', 'exist', 'className' => '\site\models\Account', 'attributeName' => 'email', 'message' => 'Account doesn\'t exist', 'on' => 'restore'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => 'Email',
            'password' => 'Password',
            'rememberMe' => 'Remember me',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors() && !$this->identity->isAuthenticated) {
            $this->addError('password', 'Incorrect username or password.');
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
