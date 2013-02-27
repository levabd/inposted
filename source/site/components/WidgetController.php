<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\components;
/**
 * base class for controller which also may be launched as widget
 *
 * @property bool   $isWidget
 * @property string $widgetId
 */
class WidgetController extends Controller
{
    private $_mode = 'controller';

    /**
     * @var integer the counter for generating implicit IDs.
     */
    private static $_counter = 0;

    /**
     * @var string id of the widget.
     */
    private $_widgetId;

    /**
     * @var \CBaseController owner/creator of this widget. It could be either a widget or a controller.
     */
    private $_owner;

    /**
     * @var string Which action to launch from widget perspective
     */
    public $action;

    public $actionParams = [];

    public function __construct($id, $module = null) {
        if ($id instanceof \CController) {
            $this->_mode = 'widget';
            $this->_owner = $id;
            $id = strtolower(substr(array_slice(explode('\\', get_class($this)), -1, 1)[0], 0, -10));
        }

        parent::__construct($id, $module);
    }

    /**
     * Returns the ID of the widget or generates a new one if requested.
     *
     * @param boolean $autoGenerate whether to generate an ID if it is not set previously
     *
     * @return string id of the widget.
     */
    public function getWidgetId($autoGenerate = true) {
        if ($this->_widgetId !== null) {
            return $this->_widgetId;
        } elseif ($autoGenerate) {
            return $this->_widgetId = 'ycw' . self::$_counter++;
        }
    }

    /**
     * Sets the ID of the widget.
     *
     * @param string $value id of the widget.
     */
    public function setWidgetId($value) {
        $this->_widgetId = $value;
    }

    /**
     * Returns the owner/creator of this widget.
     *
     * @return \CBaseController owner/creator of this widget. It could be either a widget or a controller.
     */
    public function getOwner() {
        return $this->_owner;
    }


    public function run($actionID = null) {
        if (null === $actionID) {
            $actionID = $this->action ? : $this->defaultAction;
        }
        parent::run($actionID);
    }

    public function getActionParams() {
        if ('controller' == $this->_mode) {
            return parent::getActionParams();
        }

        return $this->actionParams;
    }

    public function missingAction($actionID) {
        try {
            parent::missingAction($actionID);
        } catch (\CHttpException $e) {
            if ('controller' == $this->_mode) {
                throw $e;
            }

            throw new \CException($e->getMessage());
        }
    }

    public function invalidActionParams($action) {
        if ('controller' == $this->_mode) {
            parent::invalidActionParams($action);
        }

        throw new \CException(\Yii::t('inposted', 'Invalid params for action {action}', ['{action}' => $action->id]));
    }

    public function getIsWidget() {
        return $this->_mode == 'widget';
    }

    public function processOutput($output) {
        if (!($this->isWidget || Yii()->request->isAjaxRequest)) {
            return parent::processOutput($output);
        }
        return $output;
    }
}
