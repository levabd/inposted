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
            $interest->parent_id = $parentId;
            if (!$interest->save()) {
                $this->renderJson($interest->errors);
                return;
            }
        }

        Yii()->user->model->addInterest($interest);

        $this->renderJson(true);
    }

    public function actionAttach($id, $detach = false) {
        /** @var $interest Interest */
        if ($interest = Interest::model()->findByPk($id)) {
            /** @var $user \site\models\User */
            $user = Yii()->user->model;
            if (!$detach) {
                $user->addInterest($interest);
            } else {
                $user->removeInterest($interest);
            }
            $this->renderJson(true);
        } else {
            $this->renderJson(false, 404);
        }
    }

    public function actionIndex($verb = null, array $checked = [], $widgetId = null) {
        if ($widgetId) {
            $this->widgetId = \CHtml::encode($widgetId);
        }
        $interests = Yii()->user->model->interests;
        $this->render('own', compact('interests', 'verb', 'checked'));
    }



    public function actionRemove() {
        echo __METHOD__;
    }

    public function actionSearch($verb, array $except = [], $parentId = null) {


        $criteria = new \CDbCriteria(
            [
            'condition' => '`name` LIKE CONCAT(:verb, "%")',
            'params'    => compact('verb')
            ]
        );

        if($parentId){
            $parent = Interest::model()->findByPk($parentId);
            $criteria->compare('parent_id', $parent->id);
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
