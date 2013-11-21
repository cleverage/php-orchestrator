<?php

namespace CleverAge\Orchestrator\Cache;

use Doctrine\Common\Cache\Cache;

abstract class CacheCapable
{
    /**
     * @var Doctrine\Common\Cache\Cache
     */
    protected $cache;

    /**
     * @var array
     */
    protected $cacheLifetime = array();

    public function setCache(Cache $cache, array $cacheLifetimes = array())
    {
        $this->cache = $cache;
        $this->cacheLifetime = array_merge($this->cacheLifetime, $cacheLifetimes);

        return $this;
    }

    /**
     * @return string
     */
    abstract protected function getCachePrefix();

    /**
     * @param string    $cacheKey
     * @param string    $getMethod
     * @param array     $getArgumentes
     * @param string    $lifetimeKey
     * @return mixed
     */
    protected function getCachedRessource($cacheKey, $getMethod, array $getArgumentes, $lifetimeKey)
    {
        $cacheKey = $this->getCachePrefix().'_'.$cacheKey;

        if ($this->cache && $this->cache->contains($cacheKey)) {
            return $this->cache->fetch($cacheKey);
        }

        $result = call_user_func_array(array($this, $getMethod), $getArgumentes);

        if ($this->cache) {
            $this->cache->save($cacheKey, $result, $this->cacheLifetime[$lifetimeKey]);
        }

        return $result;
    }
}
