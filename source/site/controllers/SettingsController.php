<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\controllers;
use site\components\Controller;
use site\models\Post;

class SettingsController extends Controller
{
    public function actionIndex() {
        header('Content-Type: text/javascript');
        $settings = \CJavaScript::encode(
            [
            'baseUrl'       => Yii()->baseUrl,
            'MAX_POST_SIZE' => Post::MAX_POST_SIZE,
            'user'          => [
                'id'      => Yii()->user->id,
                'isGuest' => Yii()->user->isGuest,
            ]
            ]
        );

        echo "angular.module('inposted.services').value('settings',$settings);";
    }
}
