<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\controllers;
use site\components\WidgetController;
use site\models\Interest;

class InterestController extends WidgetController
{
    public $layout = '//interest/layout';

    public function actionCreate($name) {
        if (!($interest = Interest::model()->findByName($name))) {
            $interest = new Interest();
            $interest->name = $name;
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
        if($interest = Interest::model()->findByPk($id)){
            /** @var $user \site\models\User */
            $user = Yii()->user->model;
            if(!$detach){
                $user->addInterest($interest);
            }
            else{
                $user->removeInterest($interest);
            }
            $this->renderJson(true);
        }
        else{
            $this->renderJson(false, 404);
        }
    }

    public function actionIndex($verb = null) {
        $interests = Yii()->user->model->interests;
        $this->render('own', compact('interests', 'verb'));
    }

    public function actionVote() {
        echo __METHOD__;
    }

    public function actionRemove() {
        echo __METHOD__;
    }

    public function actionSearch($verb, $excludeOwn = true) {
        $criteria = new \CDbCriteria(
            [
            'condition' => '`name` LIKE CONCAT(:verb, "%")',
            'params'    => compact('verb')
            ]
        );

        if($excludeOwn){
            $criteria->addCondition('`id` NOT IN (SELECT `Interest_id` FROM Interest_User WHERE `User_id` = :userId)');
            $criteria->params['userId'] = Yii()->user->id;
        }
        $interests = Interest::model()->findAll($criteria);
        $this->renderPartial('side-search-results', compact('interests', 'verb'));
    }
}
