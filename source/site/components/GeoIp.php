<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\components;
/**
 * @property string $clientCountryCode;
 */
class GeoIp extends \CApplicationComponent{
    public function init(){
        if(!extension_loaded('geoip')){
            throw new \CException('geoip php extension is required');
        }
    }

    public function lookupCountryCode($address){
        return strtolower(@geoip_country_code_by_name($address));
    }

    public function getClientCountryCode() {
        return $this->lookupCountryCode(Yii()->request->userHostAddress);
    }
}
