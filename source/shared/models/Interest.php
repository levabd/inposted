<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace shared\models;
use base\ActiveRecord;

/**
 * @property Interest[] $children
 */
class Interest extends ActiveRecord
{
    public $id;
    public $name;
    public $parent_id;

    public function rules() {
        return [
            ['name', 'required'],
            ['name', 'length', 'max' => '255'],
        ];
    }

    public function relations() {
        return ['children' => [self::HAS_MANY, get_class($this), 'parent_id']];
    }

    function __toString() {
        return $this->name;
    }

    public function findByName($name, $condition = '', $params = array()) {
        return parent::findByAttributes(compact('name'), $condition, $params);
    }


}
