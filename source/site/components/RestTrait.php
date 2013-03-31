<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */

namespace site\components;


use base\ActiveRecord;

trait RestTrait {


    public function getActionParams() {
        if(in_array($this->getAction()->id, $this->restActions)){
            return $this->getJson();
        }

        return parent::getActionParams();
    }


    /**
     * @param array|ActiveRecord $models
     */
    public function renderModels($models = [], $additionlaData = []) {
        if(is_array($models)){
            $data = [];
            foreach ($models as $model) {
                $restAttributes = $model->restAttributes;
                $restAttributes = array_merge($restAttributes, $additionlaData);
                $data[] = $restAttributes;

            }
            $this->renderJson($data);
        }
        else{
            if(is_object($models)){
                $restAttributes = $models->restAttributes;
                $restAttributes = array_merge($restAttributes, $additionlaData);
                $this->renderJson($restAttributes);
            }
            else{
                $this->renderJson($models);
            }
        }
    }

    protected $_body;

    public function getJson($name = null, $defaultValue = null) {
        if (!$this->_body) {
            $this->_body = \CJSON::decode(file_get_contents('php://input'), true);
        }
        if ($this->_body && $name) {
            return array_key_exists($name, $this->_body) ? $this->_body[$name] : $defaultValue;
        }
        return $this->_body;
    }
}