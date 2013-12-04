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

    /**
     * @var boolean $enabled
     */
    protected $enabled;

    public function __construct(Cache $cache, $enabled = true)
    {
        $this->cache = $cache;
        $this->setEnabled($enabled);
    }

    public function setEnabled($enabled = true)
    {
        $this->enabled = (bool) $enabled;
    }

    public function onServicePreFetch(ServiceEvent $event)
    {
        if (!$this->enabled) {
            return;
        }

        $params = $event->getParameters();

        if (!isset($params['cache_no_get']) || !$params['cache_no_get']) {
            $cacheKey = isset($params['cache_key']) ? $event->getService()->getName().'_'.$params['cache_key'] : false;

            if ($cacheKey && $this->cache->contains($cacheKey)) {
                $event->setResource($this->cache->fetch($cacheKey));
            }
        }
    }

    public function onServicePostFetch(ServiceEvent $event)
    {
        if (!$this->enabled) {
            return;
        }

        $params = $event->getParameters();
        $cacheKey = isset($params['cache_key']) ? $event->getService()->getName().'_'.$params['cache_key'] : false;
        $lifetime = isset($params['cache_lifetime']) ? $params['cache_lifetime'] : self::DEFAULT_LIFETIME;

        if ($cacheKey && $lifetime) {
            $this->cache->save($cacheKey, $event->getResource(), $lifetime);
        }
    }
}
