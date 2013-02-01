<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\components;
class UrlShorten extends \CApplicationComponent{
    public function shorten($url) {
        //TODO: this need to be implemented
        return $url;
        $id = __METHOD__ . $url;
        if(!($short = Yii()->cache->get($id))){
            //TODO: implement this
            $short = 'http://i.co/' . \base\Randomizr::generateRandomString(5);
            Yii()->cache->set($id, $short);
        }

        return $short;
    }

    function __invoke($url) {
        return $this->shorten($url);
    }


}
