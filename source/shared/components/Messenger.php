<?php
/**
 * Date: 12/17/12 3:55 PM
 *
 * @author Dima Chukhai (dipp.dc@gmail.com, dipp@luckyteam.co.uk)
 */
namespace shared\components;
class Messenger extends \CBaseController implements \IApplicationComponent
{
    private $_initialized = false;

    public $mailerId;

    public $messages
        = array(
            'email-verification' => array(
                'subject' => 'Activate your Inposted account',
            ),
//            'email-change' => array(
//                'subject' => 'Confirm your email address',
//            ),
            'password-reset'     => array(
                'subject' => 'Set your new password',
            ),
        );

    public $addresses = array();

    public function init() {
        $this->_initialized = true;
    }

    /**
     * @return \shared\extensions\mail\Mailer
     */
    public function getMailer() {
        return Yii()->getComponent($this->mailerId);
    }

    public function send($name, $to, $data) {
        $msgOptions = array_path($this->messages, $name);
        if ($msgOptions) {
            $view = array_path($msgOptions, 'view', $name);
            /**
             * @var $html
             * @var $txt
             */
            extract($this->render($view, $data));

            $subject = array_path($msgOptions, 'subject', 'Inposted.com');
            $message = $this->getMailer()
                ->create($subject)
                ->setTo($to);

            if ($html) {
                $message->setBody($html, 'text/html');
            }
            if ($txt) {
                $message->setBody($txt, 'text/plain');
            }

            $this->getMailer()->send($message);
        }
    }

    public function getAddress($name) {
        if (array_key_exists($name, $this->addresses)) {
            return $this->addresses[$name];
        }
        throw new \Exception("No address mapped for $name");
    }

    public function sendEmailVerification($to, $options) {
        $message = $this->mailer->create('Imposted.com Email verification')
            ->setTo($to)
            ->setBody(sprintf("Email verification link %s", array_path($options, 'link')));

        $this->mailer->send($message);
    }

    /**
     * Returns the directory containing view files for this controller.
     * The default implementation returns 'protected/views/ControllerID'.
     * Child classes may override this method to use customized view path.
     * If the controller belongs to a module (since version 1.0.3), the default view path
     * is the {@link CWebModule::getViewPath module view path} appended with the controller ID.
     *
     * @return string the directory containing the view files for this controller. Defaults to 'protected/views/ControllerID'.
     */
    public function getViewPath() {
        return Yii()->getBasePath() . DIRECTORY_SEPARATOR . 'emails';
    }

    /**
     * Looks for the view script file according to the view name.
     * This method will look for the view under the widget's {@link getViewPath viewPath}.
     * The view script file is named as "ViewName.php". A localized view file
     * may be returned if internationalization is needed. See {@link CApplication::findLocalizedFile}
     * for more details.
     * Since version 1.0.2, the view name can also refer to a path alias
     * if it contains dot characters.
     *
     * @param string name of the view (without file extension)
     *
     * @return string the view file path. False if the view file does not exist
     * @see CApplication::findLocalizedFile
     */
    public function getViewFile($viewName) {
        $extension = '.php';
        if (strpos($viewName, '.')) // a path alias
        {
            $viewFile = \Yii::getPathOfAlias($viewName);
        } else {
            $viewFile = $this->getViewPath() . DIRECTORY_SEPARATOR . $viewName;
        }

        if (is_file($viewFile . $extension)) {
            return Yii()->findLocalizedFile($viewFile . $extension);
        } else {
            if ($extension !== '.php' && is_file($viewFile . '.php')) {
                return Yii()->findLocalizedFile($viewFile . '.php');
            } else {
                return false;
            }
        }
    }

    /**
     * Renders a view.
     *
     * The named view refers to a PHP script (resolved via {@link getViewFile})
     * that is included by this method. If $data is an associative array,
     * it will be extracted as PHP variables and made available to the script.
     *
     * @param string $view  name of the view to be rendered. See {@link getViewFile} for details
     * about how the view script is resolved.
     * @param array  $data  to be extracted into PHP variables and made available to the view script
     *
     * @return string the rendering result. Null if the rendering result is not required.
     * @throws \CException if the view does not exist
     * @see getViewFile
     */
    public function render($view, $data = null) {
        $return = array('txt' => false, 'html' => false);
        if (($viewFile = $this->getViewFile($view . '-txt')) !== false) {
            $return['txt'] = $this->renderInternal($viewFile, $data, true);
        }
        if (($viewFile = $this->getViewFile($view . '-html')) !== false) {
            $return['html'] = $this->renderInternal($viewFile, $data, true);
        }
        if ($return['txt'] !== false || $return['html'] !== false) {
            return $return;
        }

        throw new \CException(\Yii::t(
            'yii', '{widget} cannot find the mail template "{view}".',
            array('{widget}' => get_class($this), '{view}' => $view)
        ));
    }

    public function renderPartial($view, $data = null, $return = false) {
        if (($viewFile = $this->getViewFile($view)) !== false) {
            return $this->renderInternal($viewFile, $data, $return);
        } else {
            throw new \CException(\Yii::t(
                'yii', '{widget} cannot find the mail template "{view}".',
                array('{widget}' => get_class($this), '{view}' => $view)
            ));
        }
    }

    /**
     * @return boolean whether the {@link init()} method has been invoked.
     */
    public function getIsInitialized() {
        return $this->_initialized;
    }
}
