<?php
namespace site\components;

class InpostedUser extends \shared\components\InpostedUser
{
    protected function afterLogin($fromCookie) {
        parent::afterLogin($fromCookie);
        if($this->model->enabledHints){
            $this->setState('showHint', true);
        }
    }
}
