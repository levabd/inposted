<?php
namespace shared\components;
use Yii;
use shared\models\User;

/**
 * @property User $model
 */
class InpostedUser extends \CWebUser
{
    const ROLE_USER = 'User';
    const ROLE_ADMIN = 'Admin';

    private $_model;
    private $_roles;

    public $loginRequiredAjaxResponse = '401';

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
            throw new \CHttpException(403, Yii::t('yii', 'Требуется логин'));
        }
    }

    /**
     * @return User
     */
    public function getModel() {
        if (!$this->_model) {
            $class = Yii()->id . '\models\User';
            $this->_model = $class::model()->findByPk($this->getId());
        }

        return $this->_model;
    }

    public function getHomeUrl() {
        return $this->getState('__homeUrl', ['site:site/index']);
    }

    public function getReturnUrl($default = ['site:site/index']) {
        return parent::getReturnUrl($default);
    }

    protected function afterLogin($fromCookie) {
        $this->_roles = null;

        $this->getModel()->markAccessed();

        parent::afterLogin($fromCookie);
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
