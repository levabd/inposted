<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace base;
trait ModelTrait{
    public function formName(){
        return array_slice(explode('\\', get_class($this)), -1, 1)[0];
    }

    public function getPost(){
        return \Yii::app()->getRequest()->getPost($this->formName(), array());
    }

    public function loadPost() {
        return (bool) ($this->attributes = $this->getPost());
    }
}
