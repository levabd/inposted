<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace shared\models;
/**
 * @property string $flagUrl
 */
class Country extends \base\ActiveRecord{
    public function getFlagUrl() {
        return Yii()->urlManager->getBaseUrl('site') . "/img/flags/$this->code.jpg";
    }

    function __toString() {
        return $this->name;
    }


}
