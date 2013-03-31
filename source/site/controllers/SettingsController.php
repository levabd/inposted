<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\controllers;
use site\models\User;
use site\components\Controller;
use site\models\Post;

class SettingsController extends Controller
{
    public function actionIndex($path = null) {
        header('Content-Type: text/javascript');

        $path = $path ? explode('/', $path) : [];

        $page = [];

        if (isset($path[0])) {
            if('pm' === $path[0]){
                $page['loadPms'] = true;
            }
            elseif ('profile' === $path[0]) {
                if (isset($path[1])) {
                    $page['owner'] = User::model()->findByAttributes(['nickname' => $path[1]]);
                } else {
                    $page['owner'] = Yii()->user->model;
                }
            } elseif (is_numeric($path[0])) {
                /** @var $post Post */
                $post = Post::model()->with('author')->findByPk($path[0]);
                if ($post) {
                    $page['owner'] = $post->author;
                    $page['post'] = $post->restAttributes;
                }
            }

            if (isset($page['owner'])) {
                $page['owner'] = $page['owner']->restAttributes;
            }
        }


        $inpostedUser = Yii()->user;
        $settings = \CJavaScript::encode(
            [
            'baseUrl'       => Yii()->baseUrl,
            'MAX_POST_SIZE' => Post::MAX_POST_SIZE,
            'user'          => [
                'id'      => $inpostedUser->id,
                'isGuest' => $inpostedUser->isGuest,
                'email'   => $inpostedUser->model ? $inpostedUser->model->email : null,
            ],
            'page'          => $page,
            ]
        );

        echo "angular.module('inposted.services').value('settings',$settings);";
    }
}
