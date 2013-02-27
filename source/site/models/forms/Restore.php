<?php
namespace site\models\forms;

class Restore extends \base\FormModel
{
    public $user;
    public $nickname;
    public $username;
    public $password;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return [
            ['username', 'email'],
            ['username', 'required'],
            ['username', 'validateUser'],
            ['username', 'unsafe', 'on' => 'set-password'],

            ['password', 'required', 'on' => 'set-password'],
            ['password', 'length', 'min' => 6, 'on' => 'set-password'],
            ['password', 'compare', 'operator' => '!=', 'compareAttribute' => 'username', 'on' => 'set-password'],
            ['password', 'compare', 'operator' => '!=', 'compareAttribute' => 'nickname', 'on' => 'set-password'],
            ['password', 'site\validators\Password', 'on' => 'set-password'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => 'E-Mail',
            'password' => 'Password',
        );
    }

    public function validateUser($attribute, $params) {
        $this->user = $user = \site\models\User::model()->findByEmail($this->username);
        if (!$user) {
            $this->addError($attribute, "Account doesn't exist");
        }
        else{
            $this->nickname = $user->nickname;
        }
//        else
//        if(!$user->active){
//            $this->addError($attribute, 'Account is inactive, contact us');
//        }
//        else
//        if(!$user->verified){
//            $this->addError($attribute, 'Account email not verified, contact us');
//        }
    }
}
