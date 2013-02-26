<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\models;
class Country extends \shared\models\Country
{
    public function scopes() {
        return [
            'sort' => [
                'order'  => "FIELD(`code`, :country) DESC, name",
                'params' => ['country' => Yii()->geoip->clientCountryCode]
            ]
        ];
    }

    public function listData() {
        return \CHtml::listData($this->sort()->findAll(), 'id', 'name');
    }
}
