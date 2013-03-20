<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\controllers;
use site\components\RestTrait;
use site\components\WidgetController;
use site\models\Interest;
use site\models\User;

class InterestController extends WidgetController
{
    use RestTrait;
    public $layout = '//interest/layout';
    public $restActions = ['create'];

    public function actionCreate($name, $parentId = null) {
        if (!($interest = Interest::model()->findByName($name))) {
            $interest = new Interest();
            $interest->name = $name;
            if (!$interest->save()) {
                $this->renderJson($interest->errors);
                return;
            }
        }

        if ($parentId && ($parent = Interest::model()->findByPk($parentId))) {
            $interest->addParent($parent);
        }

        Yii()->user->model->addInterest($interest);

        $this->renderModels($interest);
    }



    public function actionAttach($id, $detach = false, $parentId = null) {
        /** @var $interest Interest */
        if ($interest = Interest::model()->findByPk($id)) {
            /** @var $user \site\models\User */
            $user = Yii()->user->model;
            if (!$detach) {
                $user->addInterest($interest);
            } else {
                $user->removeInterest($interest);
            }

            if ($parentId && ($parent = Interest::model()->findByPk($parentId))) {
                $interest->addParent($parent);
            }

            $this->renderJson(true);
        } else {
            $this->renderJson(false, 404);
        }
    }

    public function actionOwn($layout = '//interest/layout', $searchWidth = null) {
        $this->layout = $layout;
        $this->render('own', compact('searchWidth'));
    }


    public function actionRemove() {
        echo __METHOD__;
    }

    public function actionSearch($term, array $except = [], $parentId = null) {
        if ($term) {
            $term = \CHtml::encode(strip_tags($term));
        }

        $criteria = new \CDbCriteria(
            [
            'condition' => '`name` LIKE CONCAT(:term, "%")',
            'params'    => compact('term'),
            'limit' => 5,
            ]
        );

//        if(Yii()->user->id){
//            $criteria->addCondition('t.id NOT IN (SELECT Interest_id FROM Interest_User WHERE User_id = :userId)');
//            $criteria->params['userId'] = Yii()->user->id;
//        }

        if ($parentId && ($parent = Interest::model()->findByPk($parentId))) {
            $criteria->addCondition('t.id != :parentId');
            $criteria->addNotInCondition('t.id', $parent->indirectParentIds);
            $criteria->params['parentId'] = $parent->id;
            $criteria->order = "(`t`.`id` IN (SELECT `Interest_id` FROM Interest_Parent WHERE `Parent_id` = $parent->id)) DESC, `t`.`name`";
        }

        $criteria->addNotInCondition('`id`', $except);

        $interests = Interest::model()->findAll($criteria);

        $this->renderModels($interests);
    }

    public function actionChildren($parentId) {
        if ($parent = Interest::model()->findByPk($parentId)) {
            $interests = $parent->children(['limit' => 5]);
            $this->renderModels($interests);
        }
        else{
            $this->renderModels();
        }
    }

    public function actionOfUser($id) {
        if ($user = User::model()->with('interests')->findByPk($id)) {
            $this->render('of-user', ['interests' => $user->interests]);
        }
    }

    public function actionIndex($userId = null) {
        if ($userId) {
            $user = User::model()->with('interests')->findByPk($userId);
        } else {
            $user = Yii()->user->model;
        }

        $this->renderModels($user ? $user->interests : []);
    }

    public function actionExists($name) {
        $this->renderJson((bool) Interest::model()->findByName($name));
    }
}
