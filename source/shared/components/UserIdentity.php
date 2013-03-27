<?php
namespace shared\components;

use shared\models\User;

class UserIdentity extends \CUserIdentity
{
    private $user;

    public function  __construct($user) {
        if (is_string($user)) {
            $user = User::model()->findByEmail($user);
        }
        $this->user = $user;
    }

    public function authenticate() {
        return true;
    }

    public function getId() {
        return $this->user->getPrimaryKey();
    }

    public function getName() {
        return $this->user->name;
    }
}
