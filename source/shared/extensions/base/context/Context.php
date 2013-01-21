<?php
/**
 * @author Yurko Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace base\context;
class Context extends Group implements \IApplicationComponent
{
    private $_initialized = false;

    /**
     * Initializes the application component.
     * This method is required by {@link IApplicationComponent} and is invoked by application.
     * If you override this method, make sure to call the parent implementation
     * so that the application component can be marked as initialized.
     */
    public function init() {
        $this->_initialized = true;
    }

    /**
     * Checks if this application component bas been initialized.
     *
     * @return boolean whether this application component has been initialized (ie, {@link init()} is invoked).
     */
    public function getIsInitialized() {
        return $this->_initialized;
    }
}


class Group extends \CMap
{
    /**
     * @var Group[]
     */
    protected $_groups = array();

    /**
     * @param string $name
     *
     * @return Group
     */
    public function __get($name) {
        if (!isset($this->_groups[$name])) {
            $this->_groups[$name] = new static();
        }
        return $this->_groups[$name];
    }

    public function toArray() {
        $data = parent::toArray();
        foreach ($this->_groups as $name => $group) {
            if (isset($data[$name]) && is_array($data[$name])) {
                $data[$name] = \CMap::mergeArray($data[$name], $group->toArray());
            } else {
                $data[$name] = $group->toArray();
            }
        }
        return $data;
    }

    public function merge($data) {
        parent::mergeWith($data, true);
    }

    public function replace($context) {
        $this->copyFrom($context);
    }

    public function clear() {
        $this->_groups = array();
        parent::clear();
    }
}
