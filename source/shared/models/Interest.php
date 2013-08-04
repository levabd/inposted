<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace shared\models;
use base\ActiveRecord;

/**
 * @property Interest[] $children
 * @property Interest[] $parents
 */
class Interest extends ActiveRecord
{
    const PARENT_RELATION_TABLE = 'Interest_Parent';

    public $id;
    public $name;
    public $parent_id;

    public function rules() {
        return [
            ['name', 'required','message'=>'Введите интерес'],
            ['name', 'length', 'max' => '255','message'=>'Имя интереса слишком длинное'],
        ];
    }

    protected function beforeValidate() {
        $this->name = $this->prepareName($this->name);
        return parent::beforeValidate();
    }


    public function relations() {
        return [
            'children' => [self::MANY_MANY, get_class($this), self::PARENT_RELATION_TABLE . '(Parent_id, Interest_id)'],
            'parents' => [self::MANY_MANY, get_class($this), self::PARENT_RELATION_TABLE . '(Interest_id, Parent_id)'],
        ];
    }

    public function getFullName() {
        return $this->getParentsString();
    }

    public function getParentsString() {
        $result = $this->name;
        if($this->parents){
            $strings = [];
            foreach($this->parents as $parent){
                $strings[] = $parent->getParentsString();
            }
            $result .= ' <-[' . implode(', ', $strings) . ']';
        }

        return $result;
    }

    public function __toString() {
        return $this->name;
    }

    public function findByName($name, $condition = '', $params = array()) {
        $name = $this->prepareName($name);
        return parent::findByAttributes(compact('name'), $condition, $params);
    }

    protected function prepareName($name) {
        return mb_ucfirst(mb_strtolower(preg_replace('/[^\w-]/', '', str_replace(' ', '-', strip_tags(trim($name)))), Yii()->charset));
    }


}
