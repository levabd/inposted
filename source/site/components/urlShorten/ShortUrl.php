<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\components\urlShorten;
/**
 * @property string $EID
 * @method ShortUrl findByEID($eid)
 */
class ShortUrl extends \base\ActiveRecord
{
    public $id;
    public $url;

    public function behaviors() {
        return ['eid' => 'shared\behaviors\EncodedIdBehavior'];
    }

    public function findByUrl($url, $condition = '', $params = array()) {
        return parent::findByAttributes(compact('url'), $condition, $params);
    }

    function __toString() {
        return $this->url;
    }
}
