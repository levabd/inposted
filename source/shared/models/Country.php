<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace shared\models;
/**
 * @property string $flagUrl
 */
class Country extends \base\ActiveRecord
{
    public $code;
    public $name;

    public function getFlagUrl() {
        $name = str_replace(' ', '_', $this->name);
        return Yii()->urlManager->getBaseUrl('site') . "/img/flags/$name.svg";
    }

    function __toString() {
        return $this->name;
    }


}
