<?php
namespace site\components;
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends \shared\components\Controller
{
    public $author;

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
//    public $layout = '//layouts/column1';
    public $layout = '//layouts/columns';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public $showTopMenu = true;

    protected $restActions = [];

    public function init() {
        parent::init();
        if(Yii()->baseUrl){
            Yii()->clientScript->registerScript(
                'baseUrl',
                sprintf('Inposted.baseUrl = "%s";', Yii()->baseUrl),
                \CClientScript::POS_HEAD
            );
        }
    }

    public function getStaticUrl() {
        return Yii()->getBaseUrl() . '/static';
    }

    public function getContactUrl() {
        return $this->createUrl('/site/contact');
    }

    public function getSignupUrl() {
        return $this->createUrl('/auth/signup');
    }

    public function renderJson($data, $status = null) {
        if (!headers_sent()) {
            header('Content-Type: application/json');
            if ($status) {
                if (is_array($status)) {
                    $code = array_keys([$status])[0];
                    $message = array_values($status)[0];
                } else {
                    $code = $status;
                    $message = $this->getHttpHeader($code, 'Error');
                }
                header("HTTP/1.0 $code $message");
            }
        }
        echo \CJSON::encode($data);
    }

    /**
     * Return correct message for each known http error code
     *
     * @param integer $httpCode    error code to map
     * @param string  $replacement replacement error string that is returned if code is unknown
     *
     * @return string the textual representation of the given error code or the replacement string if the error code is unknown
     */
    protected function getHttpHeader($httpCode, $replacement = '') {
        $httpCodes = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            118 => 'Connection timed out',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            210 => 'Content Different',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            310 => 'Too many Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Time-out',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested range unsatisfiable',
            417 => 'Expectation failed',
            418 => 'I’m a teapot',
            422 => 'Unprocessable entity',
            423 => 'Locked',
            424 => 'Method failure',
            425 => 'Unordered Collection',
            426 => 'Upgrade Required',
            449 => 'Retry With',
            450 => 'Blocked by Windows Parental Controls',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway ou Proxy Error',
            503 => 'Service Unavailable',
            504 => 'Gateway Time-out',
            505 => 'HTTP Version not supported',
            507 => 'Insufficient storage',
            509 => 'Bandwidth Limit Exceeded',
        ];

        return array_path($httpCodes, $httpCode, $replacement);
    }

    public function controllerWidget($route, $properties = [], $captureOutput = false) {
        list($controller, $action) = explode('/', $route);
        $class = 'site\controllers\\' . ucfirst($controller) . 'Controller';
        $properties['action'] = $action;
        $this->widget($class, $properties, $captureOutput);

    }
}