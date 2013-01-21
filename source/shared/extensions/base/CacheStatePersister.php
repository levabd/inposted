<?php
/**
 * Date: 4/5/12 4:05 PM
 */
namespace base;

/**
 * Shares application state if used with shared cache storage like Memcache or DB
 * Works as ordinal state persister with other cache components
 */
class CacheStatePersister extends \CApplicationComponent implements \IStatePersister
{
    /**
     * @var string Cache component id, CMemCache or CDbCache prefered
     */
    public $cacheID = 'cache';
    public $cacheStateKey = 'base.CacheStatePersister.state';

    /**
     * Saves state data into a persistent storage.
     * @param mixed $state the state to be saved
     */
    public function save($state) {
        $this->getCache()->set($this->cacheStateKey, serialize($state));
    }

    /**
     * Loads state data from a persistent storage.
     * @return mixed the state
     */
    public function load() {
        if($value = $this->getCache()->get($this->cacheStateKey)){
            return unserialize($value);
        }

        return null;
    }

    /**
     * @return CCache
     * @throws CException
     */
    private function getCache(){
        $cache = \Yii::app()->getComponent($this->cacheID);

        if(!$cache)
            throw new \CException(\Yii::t('base.CacheStatePersister', 'Cache component `{cache}` is not configured or disabled',
                array('{cache}' => $this->cacheID)
            ));

        return $cache;
    }

}
