<?php

namespace CleverAge\Orchestrator;

use CleverAge\Orchestrator\Ticketing\Model\Ticket;

class Feature
{
    /**
     * @var CleverAge\Orchestrator\Ticketing\Model\Ticket
     */
    protected $ticket;
    /**
     * @var array<CleverAge\Orchestrator\Sources\Model\Branch>
     */
    protected $branches;

    public function __construct(Ticket $ticket, array $branches = array())
    {
        $this->ticket = $ticket;
        $this->branches = $branches;
    }

    /**
     * @return CleverAge\Orchestrator\Ticketing\Model\Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * @return array<CleverAge\Orchestrator\Sources\Model\Branch>
     */
    public function getBranches()
    {
        return $this->branches;
    }
}
