<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\controllers;
use site\components\RestTrait;
use site\models\Post;

class PostController extends \site\components\WidgetController
{
    use RestTrait;
    public $restActions = ['vote'];

    public function actionIndex(array $interests = array(), $sort = Post::SORT_DATE) {
        $criteria = new \CDbCriteria();
        if($interests){
            foreach($interests as $index => $interest){
                $criteria->addCondition("t.id IN (SELECT Post_id FROM Interest_Post WHERE Interest_id = :interest$index)");
                $criteria->params["interest$index"] = $interest;
            }
        }
        $posts = Post::model()->good()->sortBy($sort)->findAll($criteria);
        $this->renderModels($posts);
    }



    public function actionVote($id, $userVote) {
        Yii()->user->model->vote($id, $userVote);
        $post = Post::model()->findByPk($id);
        $this->renderModels($post);
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
        if($this->isWidget){
            $this->renderPartial('favorites');
        }
        else{
            $favorites = Yii()->user->model->favorites;
            $this->renderModels($favorites);
        }
    }

    public function actionToggleFavorite($id, $value = null) {
        if($value !== null){
            $value = \CJSON::decode($value);
        }
        $this->renderJson(Yii()->user->model->toggleFavorite($id, $value));
    }
}
