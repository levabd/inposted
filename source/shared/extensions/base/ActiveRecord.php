<?php
namespace base;
class ActiveRecord extends \CActiveRecord
{
    use ModelTrait;
    /**
     * Returns the static model of the specified AR class.
     * The model returned is a static instance of the AR class.
     * It is provided for invoking class-level methods (something similar to static class methods.)
     *
     * @static
     * @return static
     */
    public static function model() {
        return parent::model(get_called_class());
    }

    /**
     * Returns the name of the associated database table.
     * By default this method returns the class name as the table name.
     * You may override this method if the table is not named after this convention.
     *
     * @return string the table name
     */
    public function tableName() {
        return array_slice(explode('\\', get_class($this)), -1, 1)[0];
    }

    public function ns($classname = null){
        $nsclass = explode('\\',get_class($this));
        $class =  array_pop($nsclass);

        if($classname){
            $nsclass[] = $classname;
        }

        return  join('\\', $nsclass);
    }

    /**
     * Finds a single active record with the specified condition.
     *
     * @param mixed $condition query condition or criteria.
     *                         If a string, it is treated as query condition (the WHERE clause);
     *                         If an array, it is treated as the initial values for constructing a {@link CDbCriteria} object;
     *                         Otherwise, it should be an instance of {@link CDbCriteria}.
     * @param array $params    parameters to be bound to an SQL statement.
     *                         This is only used when the first parameter is a string (query condition).
     *                         In other cases, please use {@link CDbCriteria::params} to set parameters.
     *
     * @return static
     */
    public function find($condition = '', $params = array()) { return parent::find($condition, $params); }

    /**
     * Finds a single active record that has the specified attribute values.
     * See {@link find()} for detailed explanation about $condition and $params.
     *
     * @param array $attributes list of attribute values (indexed by attribute names) that the active records should match.
     *                          Since version 1.0.8, an attribute value can be an array which will be used to generate an IN condition.
     * @param mixed $condition  query condition or criteria.
     * @param array $params     parameters to be bound to an SQL statement.
     *
     * @return static
     */
    public function findByAttributes($attributes, $condition = '', $params = array()) {
        return parent::findByAttributes($attributes, $condition, $params);
    }

    /**
     * Finds a single active record with the specified SQL statement.
     *
     * @param string $sql    the SQL statement
     * @param array  $params parameters to be bound to the SQL statement
     *
     * @return static
     */
    public function findBySql($sql, $params = array()) { return parent::findBySql($sql, $params); }

    public function saveOrThrow($runValidation = true, $attributes = null) {
        if(parent::save($runValidation, $attributes)){
            return true;
        }

        throw new \CDbException(
            \Yii::t(
                'base','{class} model not saved: {errors}',
                array('{class}' => get_called_class(), '{errors}' => \CJSON::encode($this->errors))
            )
        );
    }
}
