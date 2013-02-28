<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\components\urlShorten;
class UrlShorten extends \CApplicationComponent
{
    public $route = 'go/go';

    public function shorten($url) {
        $id = __METHOD__ . $url;
        if (!($short = Yii()->cache->get($id))) {
            $shortUrl = ShortUrl::model()->findByUrl($url);
            if (!$shortUrl) {
                $shortUrl = new ShortUrl();
                $shortUrl->url = $url;
                $shortUrl->save();
            }

            $short = Yii()->createUrl($this->route, ['eid' => $shortUrl->EID]);
            Yii()->cache->set($id, $short);
        }

        return $short;
    }

    function __invoke($url) {
        return $this->shorten($url);
    }


}
