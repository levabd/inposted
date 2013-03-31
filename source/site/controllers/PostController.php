<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\controllers;
use site\components\RestTrait;
use site\models\Interest;
use site\models\Post;

class PostController extends \site\components\WidgetController
{
    use RestTrait;

    public $restActions = ['vote', 'index'];

    public function actionIndex(array $interests = array(), $sort = Post::SORT_DATE, $userId = null, $limit = 10, $offset = 0) {
        $criteria = new \CDbCriteria();
        $criteria->limit = $limit;
        $criteria->offset = $offset;

//        if ($interests) {
//            foreach ($interests as $index => $interest) {
//                $criteria->addCondition("t.id IN (SELECT Post_id FROM Interest_Post WHERE Interest_id = :interest$index)");
//                $criteria->params["interest$index"] = $interest;
//            }
//        }

        if ($interests = Interest::model()->findAllByPk($interests)) {
            foreach ($interests as $index => $interest) {
                $in = implode(',', array_merge($interest->getIndirectChildrenIds(), [$interest->id]));
                $criteria->addCondition("t.id IN (SELECT Post_id FROM Interest_Post WHERE Interest_id in ($in))");
            }
        }

        $criteria->compare('User_id', $userId);
        $posts = Post::model()->moderate()->good()->sortBy($sort)->findAll($criteria);
        $this->renderModels($posts);
    }


    public function actionVote($id, $userVote) {
        $post = Post::model()->findByPk($id);
        if (Yii()->user->id != $post->author->id) {
            Yii()->user->model->vote($id, $userVote);
        }
        $this->renderModels($post, ['thanks' => true]);
    }

    public function actionCreate() {
        $model = new Post;
        if ($model->attributes = $this->getJson()) {
            $model->User_id = Yii()->user->id;
            $model->save();
        }
        $this->renderModels($model);
    }

    public function actionNew() {
        $this->renderPartial('create');
    }

    public function actionView($id) {
        if (!($post = Post::model()->findByPk($id))) {
            throw new \CHttpException(404, 'Post not found');
        }
        $this->author = $post->author;
        $this->render('list');
    }

    public function actionFavorites() {
        if ($this->isWidget) {
            $this->renderPartial('favorites');
        } else {
            $favorites = Yii()->user->model->favorites;
            $this->renderModels($favorites);
        }
    }

    public function actionToggleFavorite($id, $value = null) {
        if ($value !== null) {
            $value = \CJSON::decode($value);
        }
        $this->renderJson(Yii()->user->model->toggleFavorite($id, $value));
    }
}
