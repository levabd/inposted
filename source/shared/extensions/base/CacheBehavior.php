<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */

namespace base;

/**
 * Class CacheBehavior implements one-call method for retrieving and in case it is missing filling cache value.
 *
 * @package base
 *
 * @property \CCache $owner
 */
class CacheBehavior extends \CBehavior
{
    public function load($id, $valueCallable, $expire = 0, $dependency = null) {
        if (!is_callable($valueCallable)) {
            throw new \CException("Value callable should be callable");
        }

        $value = $this->owner->get($id);
        if (false === $value) {
            $value = call_user_func($valueCallable);
            if (false !== $value) {
                $this->owner->set($id, $value, $expire, $dependency);
            }
        }

        return $value;
    }
}