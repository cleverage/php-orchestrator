<?php

namespace CleverAge\Orchestrator\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use CleverAge\Orchestrator\Events\OrchestratorEvents;
use CleverAge\Orchestrator\Events\ServiceEvent;
use CleverAge\Orchestrator\Events\ServiceErrorEvent;
use CleverAge\Orchestrator\Exception;

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
            $this->dispatcher->dispatch(OrchestratorEvents::SERVICE_FETCH_PRE, $event);

            if ($event->isResourceSet()) {
                return $event->getResource();
            }
        }

        try {
            $resource = $this->fetchResource($method, $arguments);

            if ($this->dispatcher) {
                $event->setResource($resource);
                $this->dispatcher->dispatch(OrchestratorEvents::SERVICE_FETCH_POST, $event);
            }

        } catch (\Exception $e) {
            $resource = null;

            if ($this->dispatcher) {
                $errorEvent = new ServiceErrorEvent($e);
                $errorEvent
                    ->setService($this)
                    ->setRequestMethod($method)
                    ->setRequestParameters($arguments)
                    ->setParameters($eventParameters)
                ;
                $this->dispatcher->dispatch(OrchestratorEvents::SERVICE_FETCH_ERROR, $errorEvent);

                if ($errorEvent->isSilent()) {
                    $e = null;
                } else {
                    $e = $errorEvent->getException();
                }
            }

            if ($e) {
                throw new Exception($e->getMessage(), 500, $e);
            }
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
