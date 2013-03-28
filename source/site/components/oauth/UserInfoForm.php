<?php
namespace site\components\oauth;

/**
 * HUserInfoForm used to collect username and email, when provider doesn't give it.
 * When user provides existing email, then model will ask for password and when it will be correct,
 * then user can link curren provider to the local account.
 *
 * @uses      CFormModel
 * @version   1.2.1
 * @copyright Copyright &copy; 2013 Sviatoslav Danylenko
 * @author    Sviatoslav Danylenko <dev@udf.su>
 * @license   PGPLv3 ({@link http://www.gnu.org/licenses/gpl-3.0.html})
 * @link      https://github.com/SleepWalker/hoauth
 *
 * @property \CForm $form
 */
class UserInfoForm extends \CFormModel
{
    use \base\ModelTrait;

    /**
     * @var $email
     */
    public $email;

    /**
     * @var $username
     */
    public $username;

    /**
     * @var $password
     */
    public $password;

    protected $_form = false;

    /**
     * @var \site\models\User $model the model of the User
     */
    public $model;

    /**
     * @var string $nameAtt name of the username attribute from $model
     */
    public $nameAtt;

    /**
     * @var string $emailAtt name of the username attribute from $model
     */
    public $emailAtt;

    public function rules() {
        return array(
            array('username', 'required', 'on' => 'username, username_pass, both, both_pass'),
            array('email', 'required', 'on' => 'email, email_pass, both, both_pass'),
            array('email', 'email', 'on' => 'email, email_pass, both, both_pass'),
            array('password', 'validatePassword', 'on' => 'email_pass, username_pass, both_pass'),
            array('password', 'unsafe', 'on' => 'email, username, both'),
        );
    }

    /**
     * Scenario is required for this model, and also we need info about model, that we will be validating
     *
     * @access public
     */
    public function __construct($scenario, $model, $emailAtt, $nameAtt) {
        parent::__construct($scenario);
        $this->nameAtt = $nameAtt;
        $this->emailAtt = $emailAtt;
        $this->model = $model;
    }

    public function attributeLabels() {
        return array(
            'email'    => OAuthAction::t('Email'),
            'username' => OAuthAction::t('Nickname'),
            'password' => OAuthAction::t('Password'),
        );
    }

    /**
     * Validates password, when password is correct, then sets the
     * {@link HUserInfoForm::model} variable to new User model
     *
     * @access public
     *
     * @param $attribute
     * @param $params
     *
     * @return void
     */
    public function validatePassword($attribute, $params) {
        /** @var $user \site\models\User */
        $user = $this->model->findByEmail($this->email);
        if (!$user->validatePassword($this->password)) {
            $this->addError('password', OAuthAction::t('Sorry, but password is incorrect'));
        } else {
            // setting up the current model, to use it later in OAuthAction
            if ($this->nameAtt && !isset($this->getPost()['username'])) {
                $this->username = $user->{$this->nameAtt};
            }
            $this->model = $user;
        }
    }

    /**
     * Switch to the password scenario, when we dealing with passwords
     */
    public function afterConstruct() {
        parent::afterConstruct();
        $post = $this->getPost();

        foreach (['email' => 'username', 'username' => 'email'] as $attribute => $scenario) {
            if (isset($post[$attribute])) {
                $this->scenario = str_replace($scenario, 'both', $this->scenario);
            }
        }

        if (!empty($post['password'])) {
            $this->scenario .= '_pass';
        }
    }

    /**
     * @access public
     * @return \CForm instance
     */
    public function getForm() {
        if (!$this->_form) {
            $this->_form = new Form(
                array(
                     'elements'   => array(
                         '<div class="form">',
                         $this->header,
                         'username' => array(
                             'type' => 'text',
                         ),
                         'email'    => array(
                             'type' => 'text',
                         ),
                         'password' => array(
                             'type' => 'password',
                         ),
                     ),
                     'buttons'    => array(
                         'submit' => array(
                             'type'  => 'submit',
                             'label' => OAuthAction::t('Submit'),
                         ),
                         '</div>',
                     ),
                     'activeForm' => array(
                         'id'                     => strtolower(__CLASS__) . '-form',
                         'enableAjaxValidation'   => false,
                         'enableClientValidation' => true,
                         'clientOptions'          => array(
                             'validateOnSubmit' => true,
                             'validateOnChange' => true,
                         ),
                     ),
                ), $this);
        }
        return $this->_form;
    }

    /**
     * Validate shortcut for CForm class instance
     */
    public function getIsFormValid() {
        return $this->form->submitted('submit') && $this->form->validate();
    }

    /**
     * The main function of this class. Here we validating user input with
     * provided {@link HUserInfoForm::model} class instance. We also trying
     * to catch the case, when user enters email or username of existing account.
     * In this case HUserInfoForm will be switched to `_pass` scenarios.
     *
     * @access public
     * @return boolean true if the user input is valid for both {@link HUserInfoForm::model} and HUserInfoForm models
     */
    public function validateUser() {
        $emailAtt = $this->emailAtt;
        $nameAtt = $this->nameAtt;

        if (!($this->loadPost() && $this->validate())) {
            return false;
        }

        $user = $this->model;
        $user->clearErrors();

        $validators = array();

        $attributes = [];
        if ($nameAtt) {
            $user->$nameAtt = $this->username;
            $attributes[] = $nameAtt;
            $validators = $user->getValidators($nameAtt);
        }
        if ($emailAtt) {
            $user->$emailAtt = $this->email;
            $attributes[] = $emailAtt;
            $validators = array_merge($validators, $user->getValidators($emailAtt));
        }

        $passwordRequired = false;
        foreach ($validators as $validator) {
            if (get_class($validator) != 'CUniqueValidator') {
                $validator->validate($user, $attributes);
            } else {
                $errors = $user->getErrors();
                $user->clearErrors();
                $validator->validate($user, $attributes);

                if ($user->hasErrors()) {
                    foreach (array_keys($user->getErrors()) as $attribute) {
                        if ($attribute == $emailAtt) {
                            $this->addError(
                                'email', OAuthAction::t(
                                    "This $attribute is taken by another user. If this is your account, enter password in field below or change $attribute and leave password blank."
                                )
                            );
                            $passwordRequired = true;
                        } else {
                            $this->addError('username', $user->getError($nameAtt));
                        }
                    }

                }

                $user->clearErrors();
                $user->addErrors($errors);
            }
            // we ignore uniqness checks (this checks if user with specified email or username registered),
            // because we will ask user for password, to check if this account belongs to him
        }


        $this->addErrors(
            array_filter(
                [
                'email'    => $user->getErrors($emailAtt),
                'username' => $user->getErrors($nameAtt),
                ]
            )
        );

        foreach (['email' => 'username', 'username' => 'email'] as $attribute => $scenario) {
            if ($this->hasErrors($attribute)) {
                $this->scenario = str_replace($scenario, 'both', $this->scenario);
            }
        }

        if ($passwordRequired && strpos($this->scenario, '_pass') === false) {
            $this->scenario .= '_pass';
        }

        return !$this->hasErrors();
    }

    /**
     * Transfers collected values to the {@link HUserInfoForm::model}
     *
     * @access public
     * @return void
     */
    public function sync() {
        // syncing only when we have a new model
        if ($this->model->isNewRecord && !$this->hasErrors() && strpos($this->scenario, '_pass') === false) {
            $this->model->setAttributes(
                array(
                     $this->emailAtt => $this->email,
                     $this->nameAtt  => $this->username,
                ), false
            );


            if (!$this->model->verified) {
                Yii()->controller->sendVerificationLink($this->model);
            }
        }
    }

    /**
     * Different form headers for different scenarios
     *
     * @access public
     * @return string
     */
    public function getHeader() {
        switch ($this->scenario) {
            case 'both':
            case 'both_pass':
                $header = OAuthAction::t('Please specify your nickname and email to end with registration.');
                break;
            case 'username':
            case 'username_pass':
                $header = OAuthAction::t('Please specify your nickname to end with registration.');
                break;
            case 'email':
            case 'email_pass':
                $header = OAuthAction::t('Please specify your email to end with registration.');
                break;
            default:
                $header = null;
        }

        return "<p class=\"hFormHeader\">$header</p>";
    }
}
