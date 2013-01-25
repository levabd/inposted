<?php
namespace shared\components;

class UserIdentity extends \CUserIdentity
{
    private $user;

    public function  __construct(\shared\models\User $user) {
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