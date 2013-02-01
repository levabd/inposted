<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\widgets\user;
class User extends \CWidget{
    protected $_user;
    public $view = 'user';

    public function run() {
        if($user = $this->user){
            $this->render($this->view, compact('user'));
        }
    }

    public function getUser() {
        return $this->_user ?: ($this->_user = Yii()->user->model);
    }

    public function setUser($user) {
        $this->_user = $user;
    }
}
