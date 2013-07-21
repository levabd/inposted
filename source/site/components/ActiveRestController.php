<?php
namespace site\components;

use \CJSON;

abstract class ActiveRestController extends \site\components\RestController
{
    const MAX_ITEMS_IN_INDEX = 25;

    public $modelHasAccountConstraint = true;

    abstract public function modelName();

    public function init(){
        parent::init();

        $class = $this->modelName();
        $model = $class::model();

        if(!$model instanceof \shared\interfaces\RestRecord){
            throw new \CException("$class должен реализовывать \\shared\\interfaces\\RestRecord interface");
        }

        if(null === $this->modelHasAccountConstraint){
            $this->modelHasAccountConstraint = array_key_exists('account', $model->relations());
        }
        if($this->modelHasAccountConstraint){
            if(!method_exists($model,'account')){
                throw new \CException("$class has Account constraint and should have account() scope definition");
            }
        }
    }

    /**
     * @return \CActiveRecord
     */
    public function model(){
        $class = $this->modelName();

        $model = $class::model();
        if($this->modelHasAccountConstraint){
            $model->account(User()->getAccount()->getPrimaryKey());
        }

        return $model;
    }

    public function actionCreate() {
        $m = $this->createModel();
        $m->setAttributes($this->getJson());

        if ($m->save()) {
            $this->reply($m);
        } else {
            $this->reject(400, 'Валидация', 'Ошибка валидации', $m->getErrors());
        }

    }

    public function actionUpdate($id) {
        $p = $this->getModel($id);
        $p->setAttributes($this->getJson());

        if ($p->save()) {
            $this->reply($p);
        } else {
            $this->reject(400, 'Валидация', 'Ошибка валидации', $p->getErrors());
        }
    }

    public function actionDelete($id) {
        $p = $this->getModel($id);
        $p->delete();
        $this->reply(true);
    }

    public function createModel() {
        $class = $this->modelName();

        /** @var $model \CActiveRecord */
        $model = new $class;

        if($model->hasAttribute('accountId')){
            $model->accountId = User()->getAccount()->getPrimaryKey();
        }

        return $model;
    }

    public function getModelCriteria(){
        return new \CDbCriteria;
    }

    public function getModelListCriteria(){
        $from = Yii()->getRequest()->getQuery('from',0);
        $to = Yii()->getRequest()->getQuery('to', self::MAX_ITEMS_IN_INDEX);
        $filter = Yii()->getRequest()->getQuery('filter');

        $cr = new \CDbCriteria;

        if($filter){
            $model = $this->model();
            foreach(explode(' ', $filter) as $f){
                if(false !== strpos($f, ':')){
                    list($prop, $value) = explode(':', $f, 2);
                    if($model->hasAttribute($prop) && $value){
                        $cr->compare('t.'.$prop, $value, true);
                    }
                }
            }
        }

        $cr->limit = min(abs($from - $to), self::MAX_ITEMS_IN_INDEX);
        $cr->offset = min($from, $to);

        return $cr;
    }

    public function getModel($id) {
        $class = $this->modelName();
        $cr = $this->getModelCriteria();

        if ($id) {
            $result = $this->model()->findByPk($id, $cr);

            if ($result) {
                return $result;
            }

            $this->reject(404, null, "$class #$id не найден", compact('id'));
        }
    }

    public function getModelList() {
        $cr = $this->getModelListCriteria();

        $count = $this->model()->count($cr);
        header("X-Api-Items-Total: $count");

        return $this->model()->findAll($cr);
    }

    public function transformResponse($model){
        return $model instanceof \CModel ? $model->getPublicAttributes() : $model;
    }

}

