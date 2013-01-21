<?php
namespace shared\behaviors;
class JSONAttributeBehavior extends \CActiveRecordBehavior
{
    public $attributes;
    public $assoc = true;

    public function afterFind($event) {
        $this->decodeAttributes();

        return parent::afterFind($event);
    }

    public function beforeSave($event) {
        $this->encodeAttributes();

        return parent::beforeSave($event);
    }

    public function afterSave($event) {
        $this->decodeAttributes();

        return parent::afterSave($event);
    }

    private function decodeAttributes() {
        $owner = $this->getOwner();
        foreach ($this->attributes as $name => $config) {
            if (is_numeric($name)) {
                $owner->$config = $this->decode($owner->$config);
                continue;
            }
            if (is_array($config)) {
                $class = $this->getClass($config['class']);

                if (!$class || !is_subclass_of($class, '\CModel')) {
                    throw new \Exception('JSON map config should have `class` option and class should extend \CModel');
                }

                $attrObj = new $class();
                $this->setModelAttributes($attrObj, $this->decode($owner->$name));

                $owner->$name = $attrObj;
                unset($attrObj);
            }
        }
    }

    private function getClass($class) {
        if (is_string($class)) {
            return $class;
        }
        if (is_callable($class)) {
            return call_user_func($class);
        }
    }

    private function encodeAttributes() {
        $owner = $this->getOwner();
        foreach ($this->attributes as $name => $config) {
            if (is_numeric($name)) {
                $owner->$config = $this->encode($owner->$config);
            } else {
                $owner->$name = $this->encode($owner->$name);
            }
        }
    }

    private function setModelAttributes(\CModel $model, $values) {
        foreach ($values as $name => $val) {
            try {
                $model->$name = $val;
            } catch (\Exception $e) {
                //ignore everything
            }
        }
    }

    private function encode($value) {
        return \CJSON::encode($value);
    }

    private function decode($value) {
        return \CJSON::decode($value, $this->assoc);
    }

}
