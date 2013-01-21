<?php
/**
 * @author Yurko Fedoriv <yurko.fedoriv@gmail.com>
 */

namespace base;
class HttpRequest extends \CHttpRequest
{
    public function getIsSecureConnection() {
        return parent::getIsSecureConnection() || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO']);
    }
}
