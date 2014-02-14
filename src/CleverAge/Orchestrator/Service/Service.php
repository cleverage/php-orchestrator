<?php

namespace CleverAge\Orchestrator\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use CleverAge\Orchestrator\Events\OrchestratorEvents;
use CleverAge\Orchestrator\Events\ServiceEvent;
use CleverAge\Orchestrator\Events\ServiceErrorEvent;
use CleverAge\Orchestrator\Exception;

abstract class Service
{
    const PER_PAGE_MAX = 100;
    const PER_PAGE_ALL = -1;

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

    /**
     * Fetch a multi-pages array results.
     *
     * Give a closure which takes a $limit and an $offset as parameters, must return
     * an array.
     *
     * @param \Closure $fetchOnePage
     * @param integer $limit
     * @param integer $offset
     * @return array
     */
    protected function paginate(\Closure $fetchOnePage, $limit, $offset)
    {
        $paginate = false;
        $fetchAll = $limit === static::PER_PAGE_ALL;

        if ($fetchAll || $limit > static::PER_PAGE_MAX) {
            $paginate = true;
            $loop_limit = static::PER_PAGE_MAX;
        } else {
            $loop_limit =  $limit;
        }

        $all = array();

        do {
            $results = $fetchOnePage($loop_limit, $offset);
            $nbResults = count($results);

            $all = array_merge($all, $results);
            $nbAll = count($all);

            // we have fetched too mutch results, have to slice to the expected count
            if (!$fetchAll && $nbAll > $limit) {
                $all = array_slice($all, 0, $limit);
            }

            // we have reach the end of the pool, there are less results than what we asked for
            if ($nbResults < $loop_limit) {
                break;
            }

            $offset += $nbResults;
        } while($paginate || ($nbAll < $limit));

        return $all;
    }
}
