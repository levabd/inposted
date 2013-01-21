<?php
namespace site\components;
use CJSON;
use shared\interfaces\RestRecord;

abstract class ActiveRestController extends RestController
{
    public $modelHasAccountConstraint = true;

    abstract public function modelName();

    public function init(){
        parent::init();

        $class = $this->modelName();
        $model = $class::model();

        if(!$model instanceof RestRecord){
            throw new \CException("$class should implement \\shared\\interfaces\\RestRecord interface");
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
            $this->reject(400, 'Validation', 'Validation error', $m->getErrors());
        }

    }

    public function actionUpdate($id) {
        $p = $this->getModel($id);
        $p->setAttributes($this->getJson());

        if ($p->save()) {
            $this->reply($p);
        } else {
            $this->reject(400, 'Validation', 'Validation error', $p->getErrors());
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
        $to = Yii()->getRequest()->getQuery('to',25);

        $cr = new \CDbCriteria;

        $cr->limit = abs($from - $to);
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

            $this->reject(404, 'NotFound', "$class #$id not found", compact('id'));
            Yii()->end();
        }
    }

    public function getModelList() {
        $cr = $this->getModelListCriteria();

        $count = $this->model()->count($cr);
        header("X-Api-Items-Total: $count");

        return $this->model()->findAll($cr);
    }

    public function transformResponse($model){
        return $model->getPublicAttributes();
    }

}

