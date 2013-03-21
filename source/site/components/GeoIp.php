<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\components;
/**
 * @property string $clientCountryCode;
 * @property string $clientCountryName;
 */
class GeoIp extends \CApplicationComponent{
    public function init(){
        if(!extension_loaded('geoip')){
            throw new \CException('geoip php extension is required');
        }
    }

    public function getClientCountryCode() {
        return strtolower(@geoip_country_code_by_name(Yii()->request->userHostAddress));
    }

    public function getClientCountryName(){
        return @geoip_country_name_by_name(Yii()->request->userHostAddress);
    }
}
