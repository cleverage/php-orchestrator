<?php

namespace CleverAge\Orchestrator\Events;

class ServiceErrorEvent extends ServiceEvent
{
    protected $silent = false;
    protected $exception;
    protected $originalException;

    public function __construct(\Exception $originalException = null)
    {
        $this->originalException = $originalException;
    }

    /**
     * @return boolean
     */
    public function isSilent()
    {
        return $this->silent;
    }

    public function setSilent()
    {
        $this->silent = true;
        return $this;
    }

    /**
     * @return \Return
     */
    public function getOriginalException()
    {
        return $this->originalException;
    }

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception ?: $this->originalException;
    }

    /**
     * @param \Exception $exception
     * @return \CleverAge\Orchestrator\Events\ServiceErrorEvent
     */
    public function setException(\Exception $exception)
    {
        $this->exception = $exception;

        return $this;
    }
}
