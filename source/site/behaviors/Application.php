<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\behaviors;
class Application extends \CBehavior
{
    public $profileRoute = '/user/view';

    public function createProfileUrl(\site\models\User $user) {
        /** @var $app \CWebApplication */
        $app = $this->owner;
        if ($user->id == $app->user->id) {
            return $app->createUrl($this->profileRoute);
        } else {
            return $app->createUrl($this->profileRoute, ['nickname' => $user->nickname]);
        }
    }
}
