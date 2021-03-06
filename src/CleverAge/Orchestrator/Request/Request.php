<?php

namespace CleverAge\Orchestrator\Request;

use CleverAge\Orchestrator\Model\HashUniqId;
use CleverAge\Orchestrator\Ticketing\Model\Milestone;

class Request
{
    /**
     * @var mixed
     */
    protected $status;

    /**
     * @var Orchestrator\Ticketing\Model\Milestone
     */
    protected $milestone;

    /**
     * @var mixed
     */
    protected $ticketId;

    /**
     * @var int
     */
    protected $limit = 20;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var \Closure
     */
    protected $sourceIdClosure;

    public function getTicketId()
    {
        return $this->ticketId;
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return CleverAge\Orchestrator\Ticketing\Model\Milestone
     */
    public function getMilestone()
    {
        return $this->milestone;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return array<string> List of all possible branches names
     */
    public function getSourceIdClosure()
    {
        if ($this->sourceIdClosure instanceof \Closure) {
            return $this->sourceIdClosure;
        }

        return function (\CleverAge\Orchestrator\Ticketing\Model\Ticket $ticket) {
            return array($ticket->getId());
        };
    }

    public function setTicketId($ticketId)
    {
        $this->ticketId = $ticketId;
        return $this;
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

    public function setMilestone(Milestone $milestone)
    {
        $this->milestone = $milestone;
        return $this;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        $hash = '';
        foreach (get_object_vars($this) as $property => $v) {
            $value = null;
            if (is_numeric($v)) {
                $value = $v;
            } elseif (is_string($v)) {
                $value = $v;
            } elseif (is_bool($v)) {
                $value = $v ? '1':'0';
            } elseif (is_array($v)) {
                $value = implode('-', $v);
            } elseif ($v instanceof HashUniqId) {
                $value = $v->getHashUniqId();
            }

            if ($value) {
                $hash .= '|'.$property . ':' . $value;
            }
        }

        return md5($hash);
    }
}
