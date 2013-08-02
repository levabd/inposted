<?php
namespace site\models\forms;

use site\models\User;

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
            'username' => 'E-mail',
            'password' => 'Пароль',
        );
    }

    public function validateUser($attribute, $params) {
        $user = User::model()->findByEmail($this->username);
        if (!$user) {
            $user = User::model()->findByAttributes(array('nickname' => $this->username));
        }
        $this->user = $user;
        if (!$user) {
            $this->addError($attribute, "Аккаунт не существует");
        }
        else{
            $this->nickname = $user->nickname;
        }
    }
}
