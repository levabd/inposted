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
        $baseUrl = Yii()->urlManager->getBaseUrl('site') . '/img/flags';

        return Yii()->cache->load(
            "country:flag:$this->code",
            function () use ($baseUrl) {
                $basePath = path(dirname(Yii()->request->scriptFile), 'img', 'flags');

                $id = strtoupper($this->code);
                foreach (['svg', 'png', 'jpg'] as $extension) {
                    $file = "$id.$extension";
                    if (file_exists(path($basePath, $file))) {
                        return "$baseUrl/$file";
                    }
                }

                return false;
            }
        ) ? : "$baseUrl/missing.png";
    }

    function __toString() {
        return $this->name;
    }


}
