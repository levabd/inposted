<?php
namespace shared\components;

class UserIdentity extends \CUserIdentity
{
    private $account;

    public function  __construct(\shared\models\User $account) {
        $this->account = $account;
    }

    public function authenticate() {
        return true;
    }

    public function getId() {
        return $this->account->getPrimaryKey();
    }

    public function getName() {
        return $this->account->name;
    }
}