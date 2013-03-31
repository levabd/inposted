<?php
namespace site\components;

class InpostedUser extends \shared\components\InpostedUser
{
    protected function afterLogin($fromCookie) {
        parent::afterLogin($fromCookie);
        $this->setState('showHint', true);
    }
}
