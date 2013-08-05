<?php
namespace site\components;

use site\models\Country;

class InpostedUser extends \shared\components\InpostedUser
{
    protected function afterLogin($fromCookie) {
        parent::afterLogin($fromCookie);
        if($this->model->enabledHints){
            $this->setState('showHint', true);
        }
    }

    public function getGeoipCountry(){
        return Country::model()->getByGeoip() ?: (Country::model()->sort()->find() ? : new Country());
    }
}
