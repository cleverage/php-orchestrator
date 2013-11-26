<?php

namespace CleverAge\Orchestrator\Events;

use Symfony\Component\EventDispatcher\Event;

use CleverAge\Orchestrator\Service\Service;

class ServiceEvent extends Event
{
    /**
     * @var CleverAge\Orchestrator\Service
     */
    protected $service;

    /**
     * @var string
     */
    protected $requestMethod;

    /**
     * @var mixed
     */
    protected $requestParameters;

    /**
     * @var mixed
     */
    protected $resource;

    /**
     * @var boolean
     */
    protected $resourceSet = false;

    /**
     * @var array
     */
    protected $parameters = array();

    public function getService()
    {
        return $this->service;
    }

    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    public function getRequestParameters()
    {
        return $this->requestParameters;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function setService(Service $service)
    {
        $this->service = $service;
        return $this;
    }

    public function setRequestMethod($requestMethod)
    {
        $this->requestMethod = $requestMethod;
        return $this;
    }

    public function setRequestParameters($requestParameters)
    {
        $this->requestParameters = $requestParameters;
        return $this;
    }

    public function setResource($resource)
    {
        $this->resource = $resource;
        $this->resourceSet = true;
        return $this;
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function isResourceSet()
    {
        return $this->resourceSet;
    }
}
