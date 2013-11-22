<?php

namespace CleverAge\Orchestrator\Request;

class Request
{
    /**
     * @var mixed
     */
    protected $status;

    /**
     * @var mixed
     */
    protected $milestone;

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

    public function getStatus()
    {
        return $this->status;
    }

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

    public function setMilestone($milestone)
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
            }

            if ($value) {
                $hash .= '|'.$property . ':' . $value;
            }
        }

        return md5($hash);
    }
}
