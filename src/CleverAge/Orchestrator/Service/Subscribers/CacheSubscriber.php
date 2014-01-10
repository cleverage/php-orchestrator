<?php

namespace CleverAge\Orchestrator\Service\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Doctrine\Common\Cache\Cache;
use CleverAge\Orchestrator\Events\ServiceEvent;
use CleverAge\Orchestrator\Events\OrchestratorEvents;

class CacheSubscriber implements EventSubscriberInterface
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

    public static function getSubscribedEvents()
    {
        return array(
            OrchestratorEvents::SERVICE_FETCH_PRE => array('onServicePreFetch', 255),
            OrchestratorEvents::SERVICE_FETCH_POST => array('onServicePostFetch', -255),
        );
    }

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
