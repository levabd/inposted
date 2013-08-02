<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */

namespace site\components\oauth;
use CException;
use Yii, Exception, CLogger, UserOAuth;
use site\models\User;

\Yii::import('ext.hoauth.HOAuthAction');
class OAuthAction extends \HOAuthAction
{
    public $identityClass = 'UserIdentity';
    protected $_formView = null;

    public function getFormView() {
        return $this->_formView ? : ($this->_formView = self::ALIAS . '.views.form');
    }

    public function setFormView($formView) {
        $this->_formView = $formView;
    }

    protected function populateModel($user, $profile) {
        foreach ($this->attributes as $attribute => $profileAttribute) {
            $value = null;
            if (in_array($profileAttribute, $this->_avaibleAtts)) {
                switch ($profileAttribute) {
                    case 'genderShort':
                        $value = 'male' == $profile->gender ? 'm' : 'f';
                        break;
                    case 'birthDate':
                        $value = $profile->birthYear
                            ? sprintf("%04d-%02d-%02d", $profile->birthYear, $profile->birthMonth, $profile->birthDay)
                            : null;
                        break;
                    case 'email':
                        $value = $profile->emailVerified ? : $profile->email;
                        break;
                    default:
                        $value = $profile->$profileAttribute;
                }
            } else {
                if (is_callable($profileAttribute)) {
                    $value = call_user_func($profileAttribute, $profile);
                } else {
                    $value = $profileAttribute;
                }
            }

            if (!empty($value) && !$user->$attribute) {
                $user->$attribute = $value;
            }
        }
    }


    /**
     * Initiates authorithation with specified $provider and then authenticates the user, when all goes fine
     *
     * @param mixed $provider provider name for HybridAuth
     *
     * @throws \CException
     * @access protected
     * @return void
     */
    protected function oAuth($provider) {
        try {
            // trying to authenticate user via social network
            /** @var $oAuth UserOAuth */
            $oAuth = UserOAuth::model()->authenticate($provider);
            $userProfile = $oAuth->profile;

            // If we already have a user logged in, associate the authenticated provider with the logged-in user
            if (!Yii::app()->user->isGuest) {
                $oAuth->bindTo(Yii::app()->user->id);
            } else {
                if (!$oAuth->isBond) {
                    // checking whether we already have a user with specified email
                    /** @var $user User */
                    $user = $userProfile->emailVerified ? call_user_func([$this->model, 'model'])->findByEmail($userProfile->emailVerified) : null;

                    if (!$user) {
                        // registering a new user
                        $user = new $this->model($this->scenario);
                        $this->populateModel($user, $userProfile);

                        // trying to fill email and username fields
                        if (empty($email) || $this->usernameAttribute || !$user->validate()) {
//                            if($this->usernameAttribute && $user->hasErrors($this->usernameAttribute) ){
//                                $scenario = 'both';
//                            }
//                            else{
                            $scenario = 'email';
//                            }

//                            $scenario = empty($email) && $this->usernameAttribute
//                                ? 'both' : (empty($email) ? 'email' : 'username');

                            $form = new UserInfoForm($scenario, $user, $this->_emailAttribute, $this->usernameAttribute);

                            $form->setAttributes(
                                array(
                                     'email'    => $userProfile->emailVerified ? : $userProfile->email,
                                     'username' => $userProfile->displayName,
                                ), false
                            );

                            if (!$form->validateUser()) {
                                $this->controller->render($this->formView, compact('form'));
                                Yii::app()->end();
                            }

                            // updating attributes in $user model (if needed)
                            $form->sync();

                            if ($form->model !== $user) {
                                // user provided correct password for existing account so we using the model of that account
                                $user = $form->model;
                            }
                        }

                        // the model won't be new, if user provided email and password of existing account
                        if ($user->isNewRecord && !$user->save()) {
                            throw new CException("Error, while saving {$this->model} model:\n\n" . \CJSON::encode($user->errors));
                        }
                    }
                } else {
                    // this social network account is bond to existing local account
                    Yii::log("Вошли в систему с помощью существующей ссылки через '$provider'", CLogger::LEVEL_INFO, 'hoauth.' . __CLASS__);
                    $user = call_user_func(array($this->model, 'model'))->findByPk($oAuth->user_id);
                }

                // sign user in
                $identity = new $this->identityClass($user->email, null);

                if (!Yii::app()->user->login($identity, $this->duration)) {
                    throw new CException("Не могу войти, что-то с классом UserIdentity.");
                }

                if (!$oAuth->bindTo($user->primaryKey)) {
                    throw new CException("Ошибка привязки пользователя к провайдеру:\n\n" . \CJSON::encode($oAuth->errors));
                }
            }
        } catch (Exception $e) {
            if (YII_DEBUG) {
                // Display the received error
                $code = 500;
                switch ($e->getCode()) {
                    case 0 :
                        $error = "Неизвестная ошибка.";
                        break;
                    case 1 :
                        $error = "Ошибка конфигурации Hybriauth.";
                        break;
                    case 2 :
                        $error = "Провайдер неверно сконфигурирован.";
                        break;
                    case 3 :
                        $error = "Неизвестный или заблокированный провайдер.";
                        break;
                    case 4 :
                        $error = "Отсутствуют учетные данные.";
                        break;
                    case 5 :
                        $error = "Ошибка аутентификации. Пользователь отменил аутентификацию или провайдер отменил связь.";
                        $code = 403;
                        break;
                    case 6 :
                        $error = "Провалился запрос профиля пользователя. Скорее всего, пользователь не подключен к провайдеру и он должен переподключится.";
                        break;
                    case 7 :
                        $error = "Пользователь не подключен к провайдеру.";
                        break;
                    case 8 :
                        $error = "Провайдер не поддерживает эту функцию.";
                        break;
                    default:
                        $error = '';
                }

                Yii::log($error . "\n\n<br /><br /><b>Исходное сообщение об ошибке:</b> " . $e->getMessage(), CLogger::LEVEL_WARNING, 'hoauth.' . __CLASS__);
                throw new \CHttpException($code, $error);
            }
        }
        Yii::app()->controller->redirect(Yii::app()->user->returnUrl);
    }
}