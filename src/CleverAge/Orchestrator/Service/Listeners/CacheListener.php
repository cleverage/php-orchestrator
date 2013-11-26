<?php

namespace CleverAge\Orchestrator\Service\Listeners;

use Doctrine\Common\Cache\Cache;
use CleverAge\Orchestrator\Events\ServiceEvent;

class CacheListener
{
    const DEFAULT_LIFETIME = 3600;

    /**
     * @var Doctrine\Common\Cache\Cache
     */
    protected $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function onServicePreFetch(ServiceEvent $event)
    {
        $params = $event->getParameters();
        $cacheKey = isset($params['cache_key']) ? $event->getService()->getName().'_'.$params['cache_key'] : false;

        if ($cacheKey && $this->cache->contains($cacheKey)) {
            $event->setResource($this->cache->fetch($cacheKey));
        }
    }

    public function onServicePostFetch(ServiceEvent $event)
    {
        $params = $event->getParameters();
        $cacheKey = isset($params['cache_key']) ? $event->getService()->getName().'_'.$params['cache_key'] : false;
        $lifetime = isset($params['cache_lifetime']) ? $params['cache_lifetime'] : self::DEFAULT_LIFETIME;

        if ($cacheKey && $lifetime) {
            $this->cache->save($cacheKey, $event->getResource(), $lifetime);
        }
    }
}
