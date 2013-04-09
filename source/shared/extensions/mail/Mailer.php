<?php
/**
 * Date: 12/17/12 1:05 PM
 *
 * @author Dima Chukhai (dipp.dc@gmail.com, dipp@luckyteam.co.uk)
 */
namespace shared\extensions\mail;

class Mailer extends \CApplicationComponent
{
    private $_mailer;
    private $_transport = array(
        'server' => 'localhost',
        'port' => 25,
        'encryption' => null,
        'username' => null,
        'password' => null,
    );
    private $_message = array(
        'from' => '',
    );
    private $_logger;

    public function init() {
        parent::init();

        $swiftClassPath = \Yii::getPathOfAlias('vendors.swift.lib.classes');
        require_once $swiftClassPath . DS . 'Swift.php';

        $autoloader = array('Swift', 'autoload');
        \Yii::registerAutoloader($autoloader);

        \Yii::import('vendors.swift.lib.swift_init', true);
    }

    public function setTransport(array $value){
        $this->_transport = \CMap::mergeArray($this->_transport, $value);
    }

    public function setMessage(array $value){
        $this->_message = \CMap::mergeArray($this->_message, $value);;
    }

    public function create($subject = null, $body = null, $contentType = null, $charset = null){
        $charset = $charset ?: Yii()->charset;
        return \Swift_Message::newInstance($subject, $body, $contentType, $charset)
            ->setSender($this->_message['from'])
            ->setFrom($this->_message['from']);
    }

    public function send(\Swift_Message $message, &$failures = null){
        return $this->getMailer()->send($message, $failures);
    }

    public function getMailer(){
        if(!$this->_mailer){
            $this->_mailer = \Swift_Mailer::newInstance($this->getTransport());
            $this->_mailer->registerPlugin(new \Swift_Plugins_LoggerPlugin($this->getLogger()));
        }
        return $this->_mailer;
    }

    public function getLogger(){
        if(!$this->_logger){
            $this->_logger = new \Swift_Plugins_Loggers_ArrayLogger();
        }
        return $this->_logger;
    }

    private function getTransport(){
        $type = $this->_transport['type'];
        switch($type){
            case 'smtp':
                $transport = \Swift_SmtpTransport::newInstance()
                    ->setHost($this->_transport['server'])
                    ->setPort($this->_transport['port'])
                    ->setEncryption($this->_transport['encryption'])
                    ->setUsername($this->_transport['username'])
                    ->setPassword($this->_transport['password']);
                break;
            default:
                throw new \CException("Transport of type '$type' not supported");
        }

        return $transport;
    }
}
