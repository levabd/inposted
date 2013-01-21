<?php
namespace site\components;

use \CJSON;

abstract class RestController extends \site\components\Controller
{
    private $_body;

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'roles' => array('User'),
            ),
            array(
                'deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex($id = null) {
        if ($id) {
            $c = $this->getModel($id);
        } else {
            $c = $this->getModelList();
        }
        $this->reply($c);
    }

    public function reply($data) {
        header('Content-Type: application/json');

        if(is_array($data)){
            foreach($data as &$d){
                $d = $this->transformResponse($d);
            }
        } else {
            $data = $this->transformResponse($data);
        }

        echo CJSON::encode($data);
    }

    /**
     * @param $model \CModel
     *
     * @return mixed
     */
    public function transformResponse($model){
        return $model;
    }

    public function reject($code, $type, $msg, $data) {
        header("HTTP/1.1 $code $type");
        header('Content-Type: application/json');
        echo CJSON::encode(
            array(
                 'type' => strtolower($type) . '-error',
                 'msg' => $msg,
                 'data' => $data,
            )
        );
    }

    public function getJson($name = null, $defaultValue = null) {
        if (!$this->_body) {
            $this->_body = CJSON::decode(file_get_contents('php://input'), true);
        }
        if ($name) {
            return array_key_exists($name, $this->_body) ? $this->_body[$name] : $defaultValue;
        }
        return $this->_body;
    }

    abstract public function getModel($id);
    abstract public function getModelList();
}

