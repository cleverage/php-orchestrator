<?php

namespace CleverAge\Orchestrator\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use CleverAge\Orchestrator\Events\OrchestratorEvents;
use CleverAge\Orchestrator\Events\ServiceEvent;

abstract class Service
{
    /**
     * @var Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @return string Identifier for the service
     */
    abstract public function getName();

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @return \CleverAge\Orchestrator\Service
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        return $this;
    }

    /**
     * @param mixed $method
     * @param array $arguments
     * @param array $eventParameters
     * @return mixed
     */
    protected function getResource($method, $arguments, array $eventParameters = array())
    {
        if ($this->dispatcher) {
            $event = new ServiceEvent();
            $event
                ->setService($this)
                ->setRequestMethod($method)
                ->setRequestParameters($arguments)
                ->setParameters($eventParameters)
            ;
            $this->dispatcher->dispatch(OrchestratorEvents::SERVICE_PRE_FETCH, $event);

            if ($event->isResourceSet()) {
                return $event->getResource();
            }
        }

        $resource = $this->fetchResource($method, $arguments);

        if ($this->dispatcher) {
            $event->setResource($resource);
            $this->dispatcher->dispatch(OrchestratorEvents::SERVICE_POST_FETCH, $event);
        }

        return $resource;
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    abstract protected function fetchResource($method, $arguments);
}
