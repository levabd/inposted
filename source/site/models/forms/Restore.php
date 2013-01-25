<?php
namespace site\models\forms;

class Restore extends \base\FormModel
{
    public $user;
    public $username;
    public $password;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('username', 'email'),
            array('username', 'required'),

            array('username', 'unsafe', 'on' => 'set-password'),
            array('password', 'required', 'on' => 'set-password'),

            array('username', 'validateUser'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => 'Email',
            'password' => 'Password',
        );
    }

    public function validateUser($attribute, $params) {
        $this->user = $user = \site\models\User::model()->findByEmail($this->username);
        if(!$user){
            $this->addError($attribute, 'Account doesn\'t exist');
        } else
        if(!$user->active){
            $this->addError($attribute, 'Account is inactive, contact us');
        } else
        if(!$user->verified){
            $this->addError($attribute, 'Account email not verified, contact us');
        }
    }
}
