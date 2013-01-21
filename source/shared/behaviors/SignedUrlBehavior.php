<?php
/**
 * Date: 9/5/12 3:49 PM
 *
 * @author Dima Chukhai (dipp.dc@gmail.com, dipp@luckyteam.co.uk)
 */
namespace shared\behaviors;

class SignedUrlBehavior extends \CBehavior
{
    public $queryParam = 'policy';
    public $includeIP = false;

    public function createSignedUrl($route, $policyParams = array(), $schema = '') {
        if(is_array($route)){
            $_route = array_shift($route);
            $_routeParams = $route;
        } else {
            $_route = $route;
            $_routeParams = array();
        }

        $url = array($_route, $_routeParams, $schema);

        $policy = array($url, $policyParams, time());
        if($this->includeIP){
            $ip = Yii()->getRequest()->getUserHostAddress();
            $policy[] = $ip;
        }

        $_routeParams[$this->queryParam] = $this->encryptPolicy($policy);
        return Yii()->createAbsoluteUrl($_route, $_routeParams, $schema);
    }

    /**
     * @param mixed $policy Policy data
     *
     * @return string Url safe encrypted string
     */
    public function encryptPolicy($policy) {
        $json = msgpack_pack($policy);
        $sign = substr(md5($json), 0, 4);
        return base64url_encode(Yii()->crypt->encrypt($sign.$json));
    }

    /**
     * @param string $message Encrypted string
     *
     * @return mixed|null Policy data
     */
    public function decryptPolicy($message) {
        $decrypted = Yii()->crypt->decrypt(base64url_decode($message));
        if ($decrypted) {
            $packed = substr($decrypted, 4);
            $checksum = substr($decrypted, 0, 4);
            $calculatedChecksum = substr(md5($packed), 0, 4);

            if($checksum == $calculatedChecksum){
                return msgpack_unpack($packed);
            }
        }
    }
}

