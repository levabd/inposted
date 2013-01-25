<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\widgets\user;
class User extends \CWidget{
    public function run() {
        if($user = Yii()->user->model){
            $this->render('user', compact('user'));
        }
    }
}
