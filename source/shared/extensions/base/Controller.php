<?php
/**
 * @author Yurko Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace base;
class Controller extends \CController
{
    public function createUrl($route, $params = array(), $ampersand = '&') {
        $settings = array();
        $app = \Yii::app()->id;

        if (strpos($route, ':') !== false) {
            $settings = explode(':', $route);
            $route = array_pop($settings);

            foreach ($settings as $setting) {
                if (strpos($setting, 'http') !== 0) {
                    $app = $setting;
                    break;
                }
            }

        }

        if ($app == \Yii::app()->id) {
            if ($route === '') {
                $route = $this->getId() . '/' . $this->getAction()->getId();
            } elseif (strpos($route, '/') === false) {
                $route = $this->getId() . '/' . $route;
            }
            if ($route[0] !== '/' && ($module = $this->getModule()) !== null) {
                $route = $module->getId() . '/' . $route;
            }
        }

        $settings[] = trim($route, '/');

        return \Yii::app()->createUrl(implode(':', $settings), $params, $ampersand);
    }

    public function createAbsoluteUrl($route, $params = array(), $schema = '', $ampersand = '&') {
        if (count(explode(':', $route)) == 1) {
            $route = \Yii::app()->id . ":$route";
        }

        return $this->createUrl($route, $params, $ampersand);
    }
}
