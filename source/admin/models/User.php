<?php
namespace admin\models;

class User extends \shared\models\User
{
    private $_roles;

    public function getRoles(){
        if (!$this->_roles) {
            $this->_roles = array_keys(Yii()->getAuthManager()->getRoles($this->getPrimaryKey()));
        }

        return $this->_roles;
    }
}
