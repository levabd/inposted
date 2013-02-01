<?php
/**
 * @author: Yurko Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace base\url;
/**
 * @property string host
 */
class Manager extends \CUrlManager
{
    /**
     * @var string sub-application id this url manager is associated with
     */
    public $appId;
    /**
     * @var string with schema, hostname and base url if needed http://www....
     */
    private $_host;

    /**
     * Constructs a URL.
     * If it is an alien url manager absolute url will be returned.
     *
     * @param string $route       the controller and the action (e.g. article/read)
     * @param array $params       list of GET parameters (name=>value). Both the name and value will be URL-encoded.
     *                            If the name is '#', the corresponding value will be treated as an anchor
     *                            and will be appended at the end of the URL.
     * @param string $ampersand   the token separating name-value pairs in the URL. Defaults to '&'.
     *
     * @param null|string $schema Schema to use in url. If passed, absulte url will be returned
     *
     * @param bool $forceAbsolute Whether to force absolute url creation
     *
     * @return string the constructed URL
     */
    public function createUrl($route, $params = array(), $ampersand = '&', $schema = null, $forceAbsolute = false) {
        $url = parent::createUrl($route, $params, $ampersand);

        if (!(strpos($url, 'http') === 0)) {
            if ($this->getIsAlien() || $schema || $forceAbsolute) {
                $url = $this->getHostInfo($schema) . $url;
            }
        }

        return $url;
    }

    /**
     * Returns whether current component is not associated with current sub-application
     *
     * @return bool False if this url manager associated with current sub-application
     */
    public function getIsAlien() {
        return $this->appId != \Yii::app()->id;
    }

    /**
     * Setter for $host property.
     * This will be used to generate alien urls.
     * Requires at least host info and host to be defined. e.g. http://site.com
     *
     * @param string $host
     */
    public function setHost($host) {
        $parsedUrl = parse_url(rtrim($host, '/'));
        if (!$parsedUrl || !isset($parsedUrl['scheme']) || !isset($parsedUrl['host'])) {
            throw new \CException(__CLASS__ . '::$host property should at least define schema and host information. '
                . "[$host] was passed.");
        }

        $this->_host = $parsedUrl;
    }

    /**
     * Generates host info. Uses info, that was passed to $host property.
     * If Manager::$host of current object was not cdonfigured will fallback to standart api if this is not alien url manager.
     *
     * @param string|null $schema Which schema to use. http/https
     *
     * @throws \CException
     * @return string host info
     */
    public function getHostInfo($schema = '') {
        if('http(s)' === $schema){
            $schema = \Yii::app()->getRequest()->getIsSecureConnection() ? 'https' : 'http';
        }

        if (!is_array($this->_host)) {
            if ($this->getIsAlien()) {
                throw new \CException('To generate urls in alien mode ' .
                    __CLASS__ . '::$host property must be set. Please define it.');
            }
            return \Yii::app()->getRequest()->getHostInfo($schema);
        }
        $parsedUrl = $this->_host;
        if ($schema) {
            $parsedUrl['scheme'] = $schema;
        }

        $host = $parsedUrl['scheme'] . '://';
        if (isset($parsedUrl['user'])) {
            $host .= $parsedUrl['user'];
            if (isset($parsedUrl['pass'])) {
                $host .= ":{$parsedUrl['pass']}";
            }
            $host .= '@';
        }

        $host .= $parsedUrl['host'];

        if (isset($parsedUrl['port'])) {
            $host .= ":{$parsedUrl['port']}";
        }

        return $host;
    }

    /**
     * Getter for base url. If Manager::$host was passed in configuration will use this information.
     * If not and it is not alien url manager will fallback to default value
     *
     * @param bool $absolute
     *
     * @return string
     */
    public function getBaseUrl($absolute = false) {
        if ($this->getIsAlien() || is_array($this->_host)) {
            if(is_array($this->_host)){
                $baseUrl = isset($this->_host['path']) ? '/' . $this->_host['path'] : '';
                if($absolute){
                    $baseUrl = $this->hostInfo  . $baseUrl;
                }
                return $baseUrl;
            }
            return '';
        }

        return parent::getBaseUrl();

    }
}