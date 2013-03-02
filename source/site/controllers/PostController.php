<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\controllers;
use site\models\Post;

class PostController extends \site\components\WidgetController
{
    public function actionIndex() {

    }

    public function actionVote($id, $type) {
        Yii()->user->model->vote($id, $type);
        $this->redirect(Yii()->user->returnUrl);
    }

    public function actionCreate() {
        $model = new Post;
        $request = Yii()->request;
        if (($model->attributes = $request->getPost($model->formName()))) {
            $model->User_id = Yii()->user->id;
            if ($model->save()) {
                if ($request->isAjaxRequest) {
                    header('Content-Type: application/json');
                    echo \CJSON::encode(true);
                    return;
                } else {
                    $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }
        $this->renderPartial('create', compact('model'));
    }

    public function actionView($id) {
        if (!($post = Post::model()->findByPk($id))) {
            throw new \CHttpException(404, 'Post not found');
        }

        $this->author = $post->author;
        $this->render('view', compact('post'));
    }

    public function actionFavorites() {
        $favorites = Yii()->user->model->favorites;

        $interests = [];
        foreach ($favorites as $post) {
            foreach ($post->interests as $interest) {
                if (!isset($interests[$interest->id])) {
                    $interests[$interest->id] = ['interest' => $interest, 'posts' => []];
                }
                $interests[$interest->id]['posts'][] = $post;
            }
        }

        $this->renderPartial('favorites', compact('interests'));
    }

    public function actionAddFavorite($id) {
        Yii()->user->model->addFavorite($id);
        if(!Yii()->request->isAjaxRequest){
            $this->goBack();
        }
    }

    public function actionDeleteFavorite($id) {
        Yii()->user->model->deleteFavorite($id);
        if(!Yii()->request->isAjaxRequest){
            $this->goBack();
        }
    }
}
