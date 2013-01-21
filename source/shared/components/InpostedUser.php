<?php
namespace shared\components;
use Yii;

class InpostedUser extends \CWebUser
{
    const ROLE_USER = 'User';
    const ROLE_ADMIN = 'Admin';

    private $_account;
    private $_roles;

    public $loginRequiredAjaxResponse = '401';

    public function init() {
        parent::init();
        \Yii::trace($this->getIsGuest() ? 'Guest' : join($this->getRoles(), ', '), 'user.roles');
        \Yii::trace(print_r($this->getFlashes(false), true), 'user.flashes');
        \Yii::trace(print_r($_SESSION, true), 'user.session');
        \Yii::trace($this->getReturnUrl(), 'user.returnUrl');
    }

    /**
     * Redirects the user browser to the login page.
     * Before the redirection, the current URL (if it's not an AJAX url) will be
     * kept in {@link returnUrl} so that the user browser may be redirected back
     * to the current page after successful login.
     * Support for crossite login added.
     *
     * Make sure you set {@link loginUrl}
     * so that the user browser can be redirected to the specified login URL after
     * calling this method.
     * After calling this method, the current request processing will be terminated.
     */
    public function loginRequired() {
        $request = Yii()->getRequest();

        if (!$request->getIsAjaxRequest()) {

            $controller = Yii()->controller;
            $route = "$controller->id/{$controller->action->id}";
            if ($controller->module) {
                $route = "{$controller->module->id}/$route";
            }

            $this->setReturnUrl(Yii()->createUrl(Yii()->id . ":$route", $_GET));
        } elseif (isset($this->loginRequiredAjaxResponse)) {
            echo $this->loginRequiredAjaxResponse;
            Yii()->end();
        }

        if (($url = $this->loginUrl) !== null) {
            if (is_array($url)) {
                $route = isset($url[0]) ? $url[0] : Yii()->defaultController;
                $url = Yii()->createUrl($route, array_splice($url, 1));
            }
            $request->redirect($url);
        } else {
            throw new \CHttpException(403, Yii::t('yii', 'Login Required'));
        }
    }

    /**
     * @return \shared\models\User
     */
    public function getAccount() {
        if (!$this->_account) {
            $class = sprintf('%s\models\Account', Yii()->id);
            $this->_account = $class::model()->findByPk($this->getId());
        }

        return $this->_account;
    }

    public function getHomeUrl() {
        if ($this->getIsAdmin()) {
            $home = array('admin:admin/index');
        } else {
            $home = array('site:account/index');
        }

        return $this->getState('__homeUrl', $home);
    }

    public function getReturnUrl($default = false) {
        return parent::getReturnUrl($default);
    }

    public function getRoles() {
        if (!$this->_roles) {
            $this->_roles = array_keys(Yii()->getAuthManager()->getRoles($this->getId() ? : false));
        }

        return $this->_roles;
    }

    protected function afterLogin($fromCookie) {
        $this->_roles = null;

        $this->getAccount()->setAccessed();

        parent::afterLogin($fromCookie);
    }

    public function isA($role) {
        \Yii::trace("Checking user role \"$role\"", 'user');
        return in_array($role, $this->getRoles());
    }

    public function getIsAdmin() {
        return !$this->isGuest && $this->isA('Admin');
    }

    public function getWasAdmin() {
        return $this->getState('i-was-admin');
    }

    public function setWasAdmin($value = true) {
        return $this->setState('i-was-admin', $value);
    }

    public function getIsUser() {
        return !$this->isGuest && $this->isA('User');
    }

    public static function makeA($role, $id) {
        $a = Yii()->authManager;

        try {
            if (!$a->getAuthAssignment($role, $id)) {
                $a->assign($role, $id);
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public static function makeUser($id) {
        return self::makeA(self::ROLE_USER, $id);
    }

    public static function makeAdmin($id) {
        return self::makeA(self::ROLE_ADMIN, $id);
    }

    public function setSuccess($message) {
        $this->setFlash('user.success', $message);
    }

    public function setInfo($message) {
        $this->setFlash('user.info', $message);
    }

    public function setError($message) {
        $this->setFlash('user.error', $message);
    }

    public function getSuccess() {
        return $this->getFlash('user.success');
    }

    public function getInfo() {
        return $this->getFlash('user.info');
    }

    public function getError() {
        return $this->getFlash('user.error');
    }
}
