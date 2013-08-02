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
        if(YII_DEBUG){
            header('X-Debug-Mode: On');
        }

        if(is_array($data)){
            foreach($data as &$d){
                $d = $this->transformResponse($d);
            }
        } else {
            $data = $this->transformResponse($data);
        }

        echo CJSON::encode($data);
        Yii()->end();
    }

    /**
     * @param $model \CModel
     *
     * @return mixed
     */
    public function transformResponse($model){
        return $model;
    }

    public function reject($code, $type = null, $msg = null, $data = null) {
        if(!$type){
            switch($code){
                case 400:
                    $type = 'Неверный запрос';
                    break;
                case 403: $type = 'Запрещено';
                    $msg = $msg ?: 'У вас нету доступа сюда';
                    break;
                case 404: $type = 'Страница не найдена'; break;
                case 500: $type = 'Внутренняя ошибка сервера';
                    $msg = $msg ?: 'Что-то страшное случилось. Пожалуйста, свяжитесь с нами.';
                    break;
            }
        }

        header("HTTP/1.1 $code $type");
        header('Content-Type: application/json');
        if(YII_DEBUG){
            header('X-Debug-Mode: On');
        }
        echo CJSON::encode(
            array(
                 'type' => strtolower(str_replace(' ','-',$type)) . '-error',
                 'msg' => $msg,
                 'data' => $data,
            )
        );
        Yii()->end();
    }

    public function getJson($name = null, $defaultValue = null) {
        if (!$this->_body) {
            $this->_body = CJSON::decode(file_get_contents('php://input'), true);
        }
        if ($this->_body && $name) {
            return array_key_exists($name, $this->_body) ? $this->_body[$name] : $defaultValue;
        }
        return $this->_body;
    }

    abstract public function getModel($id);
    abstract public function getModelList();
}

