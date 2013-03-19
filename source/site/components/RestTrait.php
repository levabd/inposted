<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */

namespace site\components;


trait RestTrait {


    public function getActionParams() {
        if(in_array($this->getAction()->id, $this->restActions)){
            return $this->getJson();
        }

        return parent::getActionParams();
    }


    public function renderModels($models = []) {
        if(is_array($models)){
            $data = [];
            foreach ($models as $model) {
                $data[] = $model->restAttributes;
            }
            $this->renderJson($data);
        }
        else{
            if(is_object($models)){
                $this->renderJson($models->restAttributes);
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