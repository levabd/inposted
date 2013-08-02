<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\components\urlShorten;
class Controller extends \site\components\Controller
{
    public $defaultAction = 'go';

    public function actionGo($eid) {
        /** @var $url ShortUrl */
        $url = ShortUrl::model()->findByEID($eid);
        if($url){
            $this->redirect($url->url, 303);
        }

        throw new \CHttpException(404, 'Страница не найдена');
    }
}
