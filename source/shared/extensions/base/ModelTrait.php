<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace base;
trait ModelTrait{
    public function formName(){
        return array_pop(explode('\\', get_class($this)));
    }

    public function getPost(){
        return \Yii::app()->getRequest()->getPost($this->formName(), array());
    }
}
