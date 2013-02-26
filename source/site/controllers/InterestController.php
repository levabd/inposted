<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\controllers;
use site\components\WidgetController;
use site\models\Interest;
use site\models\User;

class InterestController extends WidgetController
{
    public $layout = '//interest/layout';

    public function actionCreate($name, $parentId = null) {
        if (!($interest = Interest::model()->findByName($name))) {
            $interest = new Interest();
            $interest->name = $name;
            if (!$interest->save()) {
                $this->renderJson($interest->errors);
                return;
            }
        }

        if($parentId && ($parent = Interest::model()->findByPk($parentId))){
            $interest->addParent($parent);
        }

        Yii()->user->model->addInterest($interest);

        $this->renderJson(true);
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

            if($parentId && ($parent = Interest::model()->findByPk($parentId))){
                $interest->addParent($parent);
            }

            $this->renderJson(true);
        } else {
            $this->renderJson(false, 404);
        }
    }

    public function actionIndex($verb = null, array $checked = [], $widgetId = null, $parentId = null, $filter = false) {
        $filter = \CJSON::decode($filter);
        if($verb){
            $verb = \CHtml::encode(strip_tags($verb));
        }

        if ($widgetId) {
            $this->widgetId = \CHtml::encode($widgetId);
        }

        if($parentId){
            $parent = Interest::model()->findByPk($parentId);
        }
        else{
            $parent = null;
        }

        $interests = Yii()->user->model ? Yii()->user->model->interests : [];
        $this->render('own', compact('interests', 'verb', 'checked', 'parent', 'filter'));
    }



    public function actionRemove() {
        echo __METHOD__;
    }

    public function actionSearch($verb, array $except = [], $parentId = null) {
        if($verb){
            $verb = \CHtml::encode(strip_tags($verb));
        }

        $criteria = new \CDbCriteria(
            [
            'condition' => '`name` LIKE CONCAT(:verb, "%")',
            'params'    => compact('verb')
            ]
        );

        if($parentId && ($parent = Interest::model()->findByPk($parentId))){
            $criteria->addCondition('t.id != :parentId');
            $criteria->addNotInCondition('t.id', $parent->indirectParentIds);
            $criteria->params['parentId'] = $parent->id;
            $criteria->order = "(`t`.`id` IN (SELECT `Interest_id` FROM Interest_Parent WHERE `Parent_id` = $parent->id)) DESC, `t`.`name`";
        }
        else{
            $parent = null;
        }

        $criteria->addNotInCondition('`id`', $except);

        $interests = Interest::model()->findAll($criteria);
        $this->renderPartial('side-search-results', compact('interests', 'verb', 'parent'));
    }

    public function actionOfUser($id){
        if($user = User::model()->with('interests')->findByPk($id)){
            $this->render('of-user', ['interests' => $user->interests]);
        }
    }
}
