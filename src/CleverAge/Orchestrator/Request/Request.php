<?php

namespace CleverAge\Orchestrator\Request;

class Request
{
    protected $status;
    /**
     * @var \Closure
     */
    protected $sourceIdClosure;

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return array<string> List of all possible branches names
     */
    public function getSourceIdClosure()
    {
        if ($this->sourceIdClosure instanceof \Closure) {
            return $this->sourceIdClosure;
        }

        return function (Orchestrator\Ticketing\Model\Ticket $ticket) {
            return array($ticket->getId());
        };
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setSourceIdClosure(\Closure $sourceIdClosure)
    {
        $this->sourceIdClosure = $sourceIdClosure;
        return $this;
    }
}
