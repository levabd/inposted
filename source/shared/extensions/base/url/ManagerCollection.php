<?php
/**
 * @author: Yurko Fedoriv <yurko.fedoriv@gmail.com>
 */

namespace base\url;
use Yii, IApplicationComponent, CApplicationComponent, CMap, CLogger;

/**
 * Application component to use as urlManager to support sub-application division project architecture.
 * It can store multiple sub-application specific url managers with separate configuration
 * and allows their cross-application usage for url generation.
 *
 * Separate manager configurations should be configured in $managers property
 * with array of {@link Manager} configurations with sub-application ids as keys.
 *
 * @property array $managers
 */
class ManagerCollection extends CApplicationComponent
{
    private $_managers = array();
    private $_managerConfig = array();


    /**
     * Returns corresponding sub-application url manager, current sub-application's url manager property,
     * a property value, an event handler list or a behavior based on its name.
     * Do not call this method. This is a PHP magic method that we override
     * to allow using the following syntax to retrieve coprresponding url manager's property,
     * read a property of an object or obtain event handlers:
     *
     * @param string $name the property name or event name
     *
     * @return mixed corresponding url manager, current url manager's property value, current object's property value,
     *               event handlers attached to the event, or the named behavior
     * @throws \CException if the property or event is not defined
     * @see          __set
     */
    public function __get($name) {
        if ($this->hasManager($name)) {
            return $this->getManager($name);
        }
        elseif (isset($this->getCurrentManager()->$name)) {
            return $this->getCurrentManager()->$name;
        }
        return parent::__get($name);
    }

    /**
     * Checks if a property value is null.
     * Do not call this method. This is a PHP magic method that we override
     * to allow using isset() to detect if url manager for such sub-application is defined,
     * or current sub-application's url manager has this property or this component property is set or not.
     *
     * @param string $name the property name or the event name
     *
     * @return boolean
     */
    public function __isset($name) {
        return $this->hasManager($name) || isset($this->getCurrentManager()->$name) || parent::__isset($name);
    }


    /**
     * Getter for url manager component associated with currently running sub-application
     *
     * @return Manager
     */
    public function getCurrentManager() {
        return $this->getManager(Yii::app()->id);
    }

    /**
     * Checks whether
     *
     * @param $id
     *
     * @return bool
     */
    public function hasManager($id) {
        return isset($this->_managers[$id]) || isset($this->_managerConfig[$id]);
    }

    /**
     * Returns corresponding to $appId sub-application url manager.
     *
     * @param string $appId Name of sub-application
     *
     * @return Manager
     */
    public function getManager($appId) {
        if (isset($this->_managers[$appId])) {
            return $this->_managers[$appId];
        }

        Yii::trace("Loading url manager for '$appId' sub-application ", 'base.log.ManagerCollection');
        $config = isset($this->_managerConfig[$appId]) ? $this->_managerConfig[$appId] : array();

        if (!isset($config['class'])) {
            $config['class'] = __NAMESPACE__ . '\Manager';
        }
        $config['appId'] = $appId;

        if (!isset($config['host'])) {
            Yii::log(
                "Url manager for sub-application '$appId' does not have host configuration. You will be unable to use it in alien mode.",
                CLogger::LEVEL_WARNING,
                'base.log.ManagerCollection'
            );
        }

        /** @var $manager Manager */
        $manager = Yii::createComponent($config);
        $manager->init();
        return $this->_managers[$appId] = $manager;

    }

    /**
     * Attaches/detaches manager object in internal collection
     *
     * @param string $appId                        sub-application id to associate/disassociate manager with
     * @param \IApplicationComponent|null $manager object to associate with application or null to disassociate current value
     */
    public function setManager($appId, $manager) {
        if ($manager === null) {
            unset($this->_managers[$appId]);
        }
        else
        {
            $this->_managers[$appId] = $manager;
            if (!$manager->getIsInitialized()) {
                $manager->init();
            }
        }
    }

    /**
     * Getter for all wrapped managers
     *
     * @param bool $loadedOnly Whether to exclude configurations of managers which were not accessed and initialized yes.
     *
     * @return array
     */
    public function getManagers($loadedOnly = true) {
        if ($loadedOnly) {
            return $this->_managers;
        }
        else
        {
            return array_merge($this->_managerConfig, $this->_managers);
        }
    }

    /**
     * Setter for "managers" property. Will append additional managers to wrapped managers list or add/update configuration.
     *
     * @param array $managers Array of managers or manager configurations
     * @param bool $merge     Whether to merge onfig or to replace it.
     */
    public function setManagers(array $managers, $merge = true) {
        foreach ($managers as $id => $manager)
        {
            if ($manager instanceof IApplicationComponent) {
                $this->setManager($id, $manager);
            }
            else {
                if (isset($this->_managerConfig[$id]) && $merge) {
                    $this->_managerConfig[$id] = CMap::mergeArray($this->_managerConfig[$id], $manager);
                }
                else
                {
                    $this->_managerConfig[$id] = $manager;
                }
            }
        }
    }

    /**
     * Constructs a URL.
     * Works like CUrlManager::createUrl but understands schema and sub-application prefixes separated with ":" and will use
     * apropriate url manager for this.
     *
     * @param string $route       route to generate url for. e.g. [https:][site:]controller/action
     * @param array $params       list of GET parameters (name=>value). Both the name and value will be URL-encoded.
     *                            If the name is '#', the corresponding value will be treated as an anchor
     *                            and will be appended at the end of the URL.
     * @param string $ampersand   the token separating name-value pairs in the URL. Defaults to '&'.
     *
     * @return string the constructed URL
     */
    public function createUrl($route, $params = array(), $ampersand = '&') {
        $subApplicationRoute = explode(':', $route, 2);

        $schema = null;
        if (count($subApplicationRoute) == 2) {
            $forceAbsolute = true;
            if (in_array($subApplicationRoute[0], array('http', 'https', 'http(s)'))) {
                $schema = $subApplicationRoute[0];
                $subApplicationRoute = explode(':', $subApplicationRoute[1], 2);
                if (count($subApplicationRoute) == 1) {
                    array_unshift($subApplicationRoute, Yii::app()->id);
                }
            }
            $manager = $this->getManager($subApplicationRoute[0]);
            $route = $subApplicationRoute[1];
        }
        else {
            $forceAbsolute = false;
            $manager = $this->getCurrentManager();
        }

        return $manager->createUrl($route, $params, $ampersand, $schema, $forceAbsolute);
    }

    /**
     * Calls the named method which is not a class method.
     * Do not call this method. This is a PHP magic method that we override
     * to mimic currently running sub-application url manager and proxy calls to it.
     *
     * @param string $method       the method name
     * @param array  $params       method parameters
     *
     * @throws \CException|\Exception
     * @return mixed the method return value
     */

    public function __call($method, $params) {
        try {
            Yii::trace("Proxying [$method] call to current sub-application url manager", 'base.log.ManagerCollection');
            return call_user_func_array(array($this->getCurrentManager(), $method), $params);
        }
        catch (\CException $e) {
            if (basename($e->getFile()) == 'CComponent.php') {
                return parent::__call($method, $params);
            }
            else {
                throw $e;
            }
        }
    }

    public function getBaseUrl($absolute = false) {
        if($absolute){
            $manager = true === $absolute ? $this->getCurrentManager() : $this->getManager($absolute);
            return $manager->getBaseUrl(true);
        }
        else{
            return $this->getCurrentManager()->getBaseUrl(false);
        }
    }

    //next methods are proxies to default url manager defined to reduce magic calls number
    /**
     * Parses the user request.
     *
     * @param \CHttpRequest $request the request application component
     *
     * @return string the route (controllerID/actionID) and perhaps GET parameters in path format.
     */
    public function parseUrl($request) { return $this->getCurrentManager()->parseUrl($request); }

    /**
     * Parses a path info into URL segments and saves them to $_GET and $_REQUEST.
     *
     * @param string $pathInfo path info
     */
    public function parsePathInfo($pathInfo) { $this->getCurrentManager()->parsePathInfo($pathInfo); }

}
