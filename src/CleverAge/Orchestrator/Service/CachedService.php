<?php

namespace CleverAge\Orchestrator\Service;

abstract class CachedService extends Service
{
    /**
     * @var array
     */
    protected $cacheLifetime = array();

    public function addCacheLifetme(array $lifetimes = array())
    {
        $this->cacheLifetime = array_merge($this->cacheLifetime, $lifetimes);
        return $this;
    }
}
